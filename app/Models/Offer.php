<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'store_id',
        'category_id',
        'title',
        'description',
        'product_url',
        'image_url',
        'price',
        'code',
        'start_date',
        'end_date',
        'link',
        'is_featured',
        'is_exclusive',
        'is_deal',
        'path',
        'thumbnail',
        'sku',
        'product_name',
        'product_price',
        'old_price',
        'source',
        'external_id',
        'status',
    ];

    protected $casts = [
        'is_featured'   => 'boolean',
        'is_exclusive'  => 'boolean',
        'is_deal'       => 'boolean',
        'status'        => 'boolean',
        'start_date'    => 'date',
        'end_date'      => 'date',
        'price'         => 'decimal:2',
        'product_price' => 'decimal:2',
        'old_price'     => 'decimal:2',
    ];

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

}
