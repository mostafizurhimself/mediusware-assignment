<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use Filterable;

    protected $fillable = [
        'title', 'sku', 'description'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['product_variant_prices'];

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prices()
    {
        return $this->hasMany(ProductVariantPrice::class, 'product_id', 'id');
    }

    /**
     * Get the product variants attribute
     */
    public function getProductVariantAttribute()
    {
        return $this->variants->groupBy('variant_id')->all();
    }

    /**
     * Get the product variant prices attribute
     */
    public function getProductVariantPricesAttribute()
    {
        return $this->prices->map(function ($item) {
            return [
                'title' => $item->title,
                'price' => $item->price,
                'stock' => $item->stock,
            ];
        })->toArray();
    }
}