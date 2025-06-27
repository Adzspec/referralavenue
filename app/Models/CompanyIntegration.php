<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyIntegration extends Model
{
    protected $fillable = [
        'company_id',
        'provider',
        'credentials',
        'status',
    ];

    protected $casts = [
        'credentials' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
