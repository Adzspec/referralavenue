<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyFrontendSetting extends Model
{
    protected $fillable = [
        'company_id',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
