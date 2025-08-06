<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feature extends Model
{
    protected $fillable = ['name', 'key', 'is_value_based'];

    public function values(): HasMany
    {
        return $this->hasMany(SubscriptionFeatureValue::class);
    }
}
