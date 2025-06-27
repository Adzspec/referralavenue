<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationCategoryMapping extends Model
{
    protected $fillable = [
        'company_id',
        'provider',
        'external_category',
        'category_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
