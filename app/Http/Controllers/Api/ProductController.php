<?php

namespace App\Http\Controllers\Api;

use App\Models\Size;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Review;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //Log::info('ProductController index method called');
            
            $products = Product::with([
                'colors', 
                'sizes', 
                'category', 
                'brand',
                'reviews'
            ])->latest()->get();
            
            $colors = Color::has('products')->get();
            $sizes = Size::has('products')->get();
            $brands = Brand::has('products')->get();
            $categories = Category::has('products')->get();
    
            return response()->json([
                'products' => $products,
                'colors' => $colors,
                'sizes' => $sizes,
                'brands' => $brands,
                'categories' => $categories,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ProductController index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'error' => 'An error occurred while fetching products',
                'message' => $e->getMessage()
            ], 500);
        }
       
    }


    /**
     * Get product by slug
     */
    public function show(Product $product)
    {
        if(!$product){
            abort(404);
        }
        $product->load([
            'colors',
            'sizes',
            'category',
            'brand',
            'reviews' => function ($query) {
                $query->where('approved', 1);
            }
        ]);

        return Inertia::render('products/show', [
            'product' => new ProductResource($product),
        ]);
    }

    /**
     * Filter products by category
     */
    public function filterProductsByCategory(Category $category)
    {
        return ProductResource::collection(
            $category->products()->with(['colors','sizes','category','brand'])->latest()->get()
        )->additional([
            'colors' => Color::has('products')->get(),
            'sizes' => Size::has('products')->get(),
            'brands' => Brand::has('products')->get(),
            'categories' => Category::has('products')->get(),
            'filter' => $category->name
        ]);
    }

    /**
     * Filter products by brand
     */
    public function filterProductsByBrand(Brand $brand){
        dd($brand);

        return ProductResource::collection(
            $brand->products()->with(['colors','sizes','category','brand'])->latest()->get()
        )->additional([
            'colors' => Color::has('products')->get(),
            'sizes' => Size::has('products')->get(),
            'brands' => Brand::has('products')->get(),
            'categories' => Category::has('products')->get(),
            'filter' => $brand->name
        ]);
    }

    /**
     * Filter products by color
     */
    public function filterProductsByColor(Color $color){
        dd($color);
        return ProductResource::collection(
            $color->products()->with(['colors','sizes','category','brand'])->latest()->get()
        )->additional([
            'colors' => Color::has('products')->get(),
            'sizes' => Size::has('products')->get(),
            'brands' => Brand::has('products')->get(),
            'categories' => Category::has('products')->get(),
            'filter' => $color->name
        ]);
    }

    /**
     * Filter products by size
     */
    public function filterProductsBySize(Size $size){

        return ProductResource::collection(
            $size->products()->with(['colors','sizes','category','brand'])->latest()->get()
        )->additional([
            'colors' => Color::has('products')->get(),
            'sizes' => Size::has('products')->get(),
            'brands' => Brand::has('products')->get(),
            'categories' => Category::has('products')->get(),
            'filter' => $size->name
        ]);
    }

    /**
     * find products by search query
     */
    public function search($searchTerm)
    {
        dd($searchTerm);
        return ProductResource::collection(
            Product::where('name','LIKE','%'.$searchTerm.'%')->with(['colors','sizes','category','brand'])->latest()->get()
        )->additional([
            'colors' => Color::has('products')->get(),
            'sizes' => Size::has('products')->get(),
            'brands' => Brand::has('products')->get(),
            'categories' => Category::has('products')->get()
        ]);
    }

    
}
