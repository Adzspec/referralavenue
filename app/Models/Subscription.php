<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
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
}
