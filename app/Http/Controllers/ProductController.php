<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $products = Product::query();
        $variants = Variant::all();

        if($request->filled('title')) {
            $products = $products->where('title', $request->query('title'));
        }


        if($request->filled('date')) $products = $products->whereDate('created_at', $request->query('date'));

        $products = $products->with(['prices' => function($q) use ($request) {
            if($request->filled('price_from')) $q->where('price', '>=', $request->query('price_from'));
            if($request->filled('price_to')) $q->where('price', '<=', $request->query('price_to'));

            if($request->filled('variant')) {
                $v_id = explode(",", $request->query('variant'))[0];
                $v_label = explode(",", $request->query('variant'))[1];
                $productsVariantIds = ProductVariant::where('variant', $v_label)->where("variant_id", $v_id)->get()->pluck('id')->toArray();
                $q->where(function ($query) use ($productsVariantIds, $v_id) {
                    if($v_id == 1) return $query->whereIn('product_variant_one', $productsVariantIds);
                    if($v_id == 2) return $query->whereIn('product_variant_two', $productsVariantIds);
                    if($v_id == 3) return $query->whereIn('product_variant_three', $productsVariantIds);
                });
            }

            return $q->with('pv1', 'pv2', 'pv3');
        }]);

        if($request->filled('price_from')) {
            $products = $products->whereHas('prices', function($q) use ($request) {
                return $q->where('price', '>=', $request->query('price_from'));
            });
        }

        if($request->filled('price_to')) {
            $products = $products->whereHas('prices', function($q) use ($request) {
                return $q->where('price', '<=', $request->query('price_to'));
            });
        }

        if($request->filled('variant')) {
            $v_id = explode(",", $request->query('variant'))[0];
            $v_label = explode(",", $request->query('variant'))[1];
            $productsVariantIds = ProductVariant::where('variant', $v_label)->where("variant_id", $v_id)->get()->pluck('id')->toArray();
            $products = $products->whereHas('prices', function($q) use ($request, $productsVariantIds, $v_id) {
                return $q->where(function ($query) use ($productsVariantIds, $v_id) {
                    if($v_id == 1) return $query->whereIn('product_variant_one', $productsVariantIds);
                    if($v_id == 2) return $query->whereIn('product_variant_two', $productsVariantIds);
                    if($v_id == 3) return $query->whereIn('product_variant_three', $productsVariantIds);
                });
            });
        }

//        $products = $products->has('prices', '>', 0); // Exclude products without prices


//        dd($products->paginate()->toArray());

        $products = $products->paginate();
        return view('products.index', compact('products', 'variants'));
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
        return view('products.edit', compact('variants'));
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
