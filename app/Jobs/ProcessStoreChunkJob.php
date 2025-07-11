<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\IntegrationCategoryMapping;
use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ProcessStoreChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $companyId;
    public $channelId;
    public $chunk;

    public function __construct($companyId, $channelId, $chunk)
    {
        $this->companyId = $companyId;
        $this->channelId = $channelId;
        $this->chunk = $chunk;
    }

    public function handle()
    {
        foreach ($this->chunk as $app) {
            if (($app['relationStatus'] ?? null) !== 'active') continue;

            // Format category name and create/find category
            $formattedCategoryName = Str::headline($app['categoryId'] ?? '');
            $this->createCategory($app['categoryId'] ?? '');

            // Check for existing store by channelId and name
            $exists = Store::where('channelId', $this->channelId)
                ->where('company_id', $this->companyId)
                ->where('name', $app['displayName'] ?? '')
                ->exists();
            if ($exists) continue;

            Store::create([
                'company_id'   => $this->companyId,
                'name'         => $app['displayName'] ?? null,
                'image'        => $app['logoImageFilename'] ?? null,
                'status'       => 1,
                'channelId'    => $this->channelId ?? null,
                'channelName'  => 'VeckansR',
                'programId'    => $app['id'] ?? null,
                'categoryName' => $formattedCategoryName,
                // Optionally link by category_id if you want (recommended):
                // 'category_id' => $category->id,
            ]);
        }
    }

    public function createCategory(string $categoryName): Category
    {
        // Format category name to "Title Case" with spaces between words
        $formatted = Str::headline($categoryName); // e.g., "electronicsHome" → "Electronics Home"

        // Atomically find or create the category for this company
        return Category::firstOrCreate(
            [
                'company_id' => $this->companyId,
                'name'       => $formatted,
            ]
        // Add more default fields as needed, e.g., 'slug' => Str::slug($formatted)
        );
    }
}
