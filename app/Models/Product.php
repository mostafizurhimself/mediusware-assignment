<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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
     * The variants that belong to the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(Variant::class, 'product_variants');
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productVariants()
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
        return $this->variants->unique()->each(function ($variant) {
            return $variant['tags'] = $variant->productVariants()->where('product_id', $this->id)->get()->pluck('variant');
        })->each(function ($variant) {
            return $variant['option'] = $variant->id;
        })->toArray();
    }

    /**
     * Get the product variant prices attribute
     */
    public function getProductVariantPricesAttribute()
    {
        return $this->prices->map(function ($item) {
            return [
                'id'    => $item->id,
                'title' => $item->title,
                'price' => $item->price,
                'stock' => $item->stock,
            ];
        })->toArray();
    }

    /**
     * Get image urls attribute
     */
    public function getImageUrlsAttribute()
    {
        return $this->images->map(function ($image) {
            return [
                "name" => basename($image->file_path),
                "size" => Storage::disk('public')->size($image->file_path),
                "url" => asset(Storage::url($image->file_path))
            ];
        })->toArray();
    }
}