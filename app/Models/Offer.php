<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'company_id',
        'title',
        'description',
        'product_url',
        'image_url',
        'price',
        'source',
        'external_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'offer_category');
    }
}
