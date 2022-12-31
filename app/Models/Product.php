<?php

namespace App\Models;

use App\Models\Variant;
use App\Models\ProductImage;
use App\Models\ProductVariantPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];

    public function images() {
        return $this->hasOne(ProductImage::class, 'product_id', 'id');
    }

    public function variantPrices(){
        return $this->hasMany(ProductVariantPrice::class, 'product_id', 'id');
    }

    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(Variant::class, 'product_variants','product_id','variant_id')
        ->wherePivot('deleted_at', null)
        ->withPivot('id','variant_id','product_id','variant')
        ->withTimestamps();
    }

}
