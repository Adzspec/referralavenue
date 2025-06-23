<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_subscription_id',
        'amount',
        'payment_method',
        'payment_status',
        'transaction_id',
        'paid_at',
    ];

    protected $dates = [
        'paid_at',
    ];

    public function companySubscription()
    {
        return $this->belongsTo(CompanySubscription::class);
    }
} 