<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_id',
        'price',
        'duration',
        'features',
        'status',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_subscriptions')
            ->withPivot(['start_date', 'end_date', 'status'])
            ->withTimestamps();
    }

    // SubscriptionPlan.php
    public function features(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'subscription_feature');
    }

    public function featureValues()
    {
        return $this->hasMany(SubscriptionFeatureValue::class);
    }

    public function getFeatureValue(string $key)
    {
        $featureValue = $this->featureValues
            ->firstWhere(fn($fv) => $fv->feature->key === $key);
        if (!$featureValue) {
            return false;
        }
        return $featureValue->value;
    }
}
