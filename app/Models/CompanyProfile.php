<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'phone',
        'address',
        'zipcode',
        'city',
        'state',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
