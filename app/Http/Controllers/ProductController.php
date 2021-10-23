<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Variant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariantPrice;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $products = Product::filter($request->all())->with('variants', 'prices.variantOne', 'prices.variantTwo', 'prices.variantThree')->paginate(5);
        return view('products.index', [
            'products' => $products,
            'variants' => Variant::with('productVariants')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $product = Product::create($request->only('title', 'sku', 'description'));
            // Add variants
            foreach ($request->get('product_variant') as $variant) {
                foreach ($variant['tags'] as $tag) {
                    $product->variants()->create(['variant' => $tag, 'variant_id' => $variant['option']]);
                }
            }

            // Add Price variants
            foreach ($request->get('product_variant_prices') as $price) {
                $variant = explode('/', $price['title']);
                $product->prices()->create([
                    'product_variant_one'   => $variant[0] ? $product->variants()->where('variant', $variant[0])->first()->id : null,
                    'product_variant_two'   => $variant[1] ? $product->variants()->where('variant', $variant[1])->first()->id : null,
                    'product_variant_three' => $variant[2] ? $product->variants()->where('variant', $variant[2])->first()->id : null,
                    'price'                 => $price['price'],
                    'stock'                 => $price['stock'],
                ]);
            }

            // Add product images{
            foreach ($request->get('product_image') as $imgString) {
                $path = ProductImage::uploadFile($imgString);
                $product->images()->create([
                    'file_path' => $path
                ]);
            }

            return $product;
        });
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', [
            'variants' => $variants,
            'product'  => $product->load('images', 'variants', 'prices.variantOne', 'prices.variantTwo', 'prices.variantThree')->append('product_variant', 'product_variant_prices')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}