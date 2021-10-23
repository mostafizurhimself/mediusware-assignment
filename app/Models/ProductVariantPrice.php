<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{
    protected $fillable = [
        'product_variant_one', 'product_variant_two', 'product_variant_three', 'price', 'stock', 'product_id'
    ];

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variantOne()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_one', 'id')->withDefault(['variant' => '']);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variantTwo()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_two', 'id')->withDefault(['variant' => '']);
    }

    /**
     * Determines one-to-many relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variantThree()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_three', 'id')->withDefault(['variant' => '']);
    }

    /**
     * Get the title property
     */
    public function getTitleAttribute()
    {
        return $this->variantOne->variant . "/" . $this->variantTwo->variant . "/" . $this->variantThree->variant;
    }
}