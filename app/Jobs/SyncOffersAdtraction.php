<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Category;
use App\Models\Company;
use App\Models\Store;
use App\Services\AdtractionApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class SyncOffersAdtraction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int|string */
    protected $channelId;
    /** @var int|string */
    protected $companyId;

    /** @var AdtractionApiService */
    protected $api;

    /** How many times to try the job */
    public $tries = 3;

    /** Backoffs between retries (seconds) */
    public $backoff = [60, 300, 900];

    /** Fail the job if it runs longer than this (seconds) */
    public $timeout = 120;

    public function __construct($channelId, $companyId)
    {
        $this->channelId = $channelId;
        $this->companyId = $companyId;
    }

    /**
     * Prevent concurrent runs for the same company+channel.
     */
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping("adtraction:{$this->companyId}:{$this->channelId}"))
                ->expireAfter(600), // 10 min lock safety
        ];
    }

    public function handle(): void
    {
        $company = Company::find($this->companyId);

        if (!$company) {
            Log::error('Adtraction sync: company not found', ['company_id' => $this->companyId]);
            return;
        }

        $integration = $company->integrations()
            ->where('provider', 'adtraction')
            ->first();

        $token = data_get($integration, 'credentials.api_token');

        if (empty($integration) || empty($token)) {
            Log::warning('Adtraction sync: missing integration or token', ['company_id' => $company->id]);
            return;
        }

        $this->api = new AdtractionApiService($token);
        $stores = Store::query()
            ->where('channelName','adtraction')
            ->whereNotNull('productFeedId')
            ->get();
        foreach ($stores as $store) {
            ProcessStoreOfferJob::dispatch($store->id,$token);
        }

        Log::info('Adtraction sync: stores synced successfully', [
            'company_id' => $this->companyId,
            'channel_id' => $this->channelId,
        ]);
    }

    /**
     * Called if the job ultimately fails.
     */
    public function failed(\Throwable $e): void
    {
        Log::error('Adtraction sync failed', [
            'company_id' => $this->companyId,
            'channel_id' => $this->channelId,
            'error'      => $e->getMessage(),
        ]);
    }
}
