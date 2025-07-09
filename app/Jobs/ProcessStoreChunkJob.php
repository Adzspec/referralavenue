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
            if ($app['relationStatus'] == 'active') {
                $this->createCategory($app['categoryId']);
                $exists = Store::where('channelId', $this->channelId)
                    ->where('name', $app['displayName'])->exists();
                if ($exists) continue;

                $formatted = preg_replace('/([a-z])([A-Z])/', '$1 & $2', $app['categoryId']);
                $formatted = ucwords($formatted);
                Store::create([
                    'company_id'   => $this->companyId,
                    'name'         => $app['displayName'] ?? null,
                    'image'        => $app['logoImageFilename'],
                    'status'       => 1,
                    'channelId'    => $this->channelId ?? null,
                    'channelName'  => 'VeckansR',
                    'programId'    => $app['id'],
                    'categoryName' => $formatted ?? null,
                ]);
            }
        }
    }

    public function createCategory($cagetoryName)
    {
        $formatted = preg_replace('/([a-z])([A-Z])/', '$1 & $2', $cagetoryName);
        $formatted = ucwords($formatted);
//        $category = IntegrationCategoryMapping::query()
//            ->where('company_id',$this->companyId)
//            ->where('provider','addrevenue')
//            ->where('external_category',$formatted)->first();
        $category = Category::query()
            ->where('company_id',$this->companyId)
            ->where('name',$formatted)->first();
        if (!$category) {
            $category = new Category();
            $category->name = $formatted;
            $category->company()->associate($this->companyId);
            $category->save();
//            $category = new IntegrationCategoryMapping();
//            $category->external_category = $formatted;
//            $category->provider = 'addrevenue';
//            $category->save();
        }
    }
}
