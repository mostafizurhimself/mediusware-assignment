<?php

namespace App\Filters;

use EloquentFilter\ModelFilter;
use Illuminate\Support\Facades\DB;

class ProductFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function title($value)
    {
        $this->where('title', 'like', "%$value%");
    }

    public function priceFrom($value)
    {
        $this->whereHas('prices', function ($query) use ($value) {
            $query->where('price', ">=", $value);
        });
    }

    public function priceTo($value)
    {
        $this->whereHas('prices', function ($query) use ($value) {
            $query->where('price', "<=", $value);
        });
    }

    public function date($value)
    {
        $this->whereDate('created_at', $value);
    }

    public function variant($value)
    {
        $this->whereHas('variants', function ($query) use ($value) {
            $query->where('variant', "<=", $value);
        });
    }
}