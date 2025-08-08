<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'domain',
        'registration_no',
        'vat',
        'status',
    ];

    public function profile()
    {
        return $this->hasOne(CompanyProfile::class);
    }

    public function frontendSetting()
    {
        return $this->hasOne(CompanyFrontendSetting::class);
    }

    public function integrations()
    {
        return $this->hasMany(CompanyIntegration::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(CompanySubscription::class);
    }

    // Company.php
    public function latestSubscription()
    {
        return $this->hasOne(CompanySubscription::class)->latestOfMany()->with('subscription.featureValues.feature');
    }
}
