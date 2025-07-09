<?php

namespace App\Jobs;

use App\Models\Offer;
use App\Models\Store;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncAddrevenueCampaignsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $campaigns;
    protected $channelId;

    /**
     * Create a new job instance.
     */
    public function __construct(array $campaigns, $channelId)
    {
        $this->campaigns = $campaigns;
        $this->channelId = $channelId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $stores = Store::query()->where('channelId', $this->channelId)->get()->keyBy('programId');

        foreach ($this->campaigns as $campaign) {
            if (isset($campaign['id']) && isset($stores[$campaign['advertiserId']])) {
                $store = $stores[$campaign['advertiserId']];

                $exists = Offer::query()->where('title', $campaign['description'] ?? '')
                    ->where('company_id', $store->company_id)
                    ->where('store_id', $store->id)
                    ->exists();
                if ($exists) {
                    continue;
                }
                // Create new coupon
                $coupon = new Offer();
                $coupon->company()->associate($store->company);
                $coupon->title = $campaign['description'] ?? null;
                $coupon->description = $campaign['description'] ?? null;
                $coupon->store()->associate($store->id);
                $coupon->link = $campaign['trackingLink'] ?? null;
                $coupon->code = $campaign['discountCode'] ?? null;
                $coupon->is_deal = empty($campaign['discountCode']) ? 1 : 0;
                $coupon->path = preg_replace('/[^a-zA-Z0-9]/', '', $campaign['description'] ?? '');
                $coupon->status = 1;
                $coupon->type = 'campaign';

                if (isset($campaign['validFrom'])) {
                    $coupon->start_date = Carbon::parse($campaign['validFrom']);
                }
                if (isset($campaign['validTo'])) {
                    $coupon->end_date = $campaign['validTo'] == '2045-01-01'? null : Carbon::parse($campaign['validTo']);
                }
                if (isset($campaign['imageUrl'])) {
                    $coupon->thumnail = $campaign['imageUrl'];
                }
                $coupon->save();            }
        }
    }
}

