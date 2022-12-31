<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariantPrice;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $products = Product::with('images','variants','variantPrices')->get();
        return view('products.index', compact('products'));
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
        $messages = array(
            'title.required'  => 'Enter product name',
            'sku.required'    => 'Enter product sku',
        );

        $this->validate($request, array(
            'title'           => 'required|string',
            'sku'             => 'required|string|unique:products,sku,NULL,id,deleted_at,NULL',
            'product_image.*' => 'required|max:2048|mimes:jpeg,png,jpg,gif',
            'description'     => 'nullable'
        ), $messages);

        DB::beginTransaction();

        try{
            $product = new Product();
            $product->title        = $request->title;
            $product->sku          = $request->sku;
            $product->description  = $request->description;
            $product->save();

            //product's image store
            // $productImage = new ProductImage();
            // if ($request->file('product_image')) {
            //     $file = $request->file('product_image');
            //     $filename = time() . $file->getClientOriginalName();
            //     $file->move(public_path('/img/'), $filename);
            //     $productImage->file_path = $filename;
            // }

            // $productImage->product_id  = $product->id;
            // $productImage->save();

        foreach($request->product_variant as $variants) {
            $value = [];
            foreach($variants['tags'] as $tag) {
                $value['variant'] = $tag;
                $value['product_id'] = $product->id;
                $value['variant_id'] = $variants['option'];
                $productVariant = ProductVariant::create($value);
            }
        }

        $item = [];
        foreach ($request->product_variant_prices as $productVariantPrice) {
            $item['product_variant_one'] = $productVariant->id;
            $item['product_variant_two'] = $productVariant->id;
            $item['product_variant_three'] = $productVariant->id;
            $item['product_id'] = $product->id;
            $item['price'] = $productVariantPrice['price'];
            $item['stock'] = $productVariantPrice['stock'];
            ProductVariantPrice::create($item);
        }

        DB::commit();

        return redirect()->route('product.index')
                ->with('success', 'Product created successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('images','variants','variantPrices')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::with('images','variants','variantPrices')->findOrFail($id);
        $variantDatas = Variant::all();
        return view('products.update', compact('product','variantDatas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = array(
            'title.required'  => 'Enter product name',
            'sku.required'    => 'Enter product sku',
        );

        $this->validate($request, array(
            'title'           => 'required|string',
            'sku'             => 'required|unique:products,sku,' . $id . ',id,deleted_at,NULL',
            'product_image.*' => 'required|max:2048|mimes:jpeg,png,jpg,gif',
            'description'     => 'nullable'
        ), $messages);

        DB::beginTransaction();

        try{
            $product = Product::findOrFail($id);
            $product->title        = $request->title;
            $product->sku          = $request->sku;
            $product->description  = $request->description;
            $product->update();

            //product's image store
            // $productImage = new ProductImage();
            // if ($request->file('product_image')) {
            //     $file = $request->file('product_image');
            //     $filename = time() . $file->getClientOriginalName();
            //     $file->move(public_path('/img/'), $filename);
            //     $productImage->file_path = $filename;
            // }

            // $productImage->product_id  = $product->id;
            // $productImage->save();

        foreach($request->product_variant as $variants) {
            $value = [];
            foreach($variants['tags'] as $tag) {
                $value['variant'] = $tag;
                $value['product_id'] = $product->id;
                $value['variant_id'] = $variants['option'];
                $productVariant = ProductVariant::create($value);
            }
        }

        $item = [];
        foreach ($request->product_variant_prices as $productVariantPrice) {
            $item['product_variant_one'] = $productVariant->id;
            $item['product_variant_two'] = $productVariant->id;
            $item['product_variant_three'] = $productVariant->id;
            $item['product_id'] = $product->id;
            $item['price'] = $productVariantPrice['price'];
            $item['stock'] = $productVariantPrice['stock'];
            ProductVariantPrice::create($item);
        }

        DB::commit();

        return redirect()->route('product.index')
                ->with('success', 'Product updated successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $data = $product->delete();
            return back()->with('message', 'Product deleted successfully');
                if ($data) {
                    ProductVariant::where('product_id', $product)->delete();
                    ProductVariantPrice::where('product_id', $product)->delete();
                    return redirect()->route('production.index')
                    ->with('success', 'Production deleted successfully');
                }

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
