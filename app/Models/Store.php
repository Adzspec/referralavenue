<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'image',
        'description',
        'status',
        'channelId',
        'channelName',
        'programId',
        'categoryName',
        'categoryId',
        'productFeedId',
    ];

    /**
     * Relationship: Store belongs to a Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
