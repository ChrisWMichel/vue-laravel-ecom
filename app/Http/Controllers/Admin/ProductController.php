<?php

namespace App\Http\Controllers\Admin;

use App\Models\Size;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('admin/products/index')->with([
            'products' => Product::with(['colors', 'sizes', 'category', 'brand'])->latest()->paginate(10)
         ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        $brands = Brand::all();
        return Inertia::render('admin/products/create')->with([
            'categories' => $categories,
            'colors' => $colors,
            'sizes' => $sizes,
            'brands' => $brands
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddProductRequest $request)
    {
        if($request->validated()) {
            $data = $request->all();
            $data['thumbnail'] = $this->saveImage($request->file('thumbnail'));
            $data['price'] = number_format((float)$data['price'], 2, '.', '');

            if($request->hasFile('first_image')) {
                $data['first_image'] = $this->saveImage($request->file('first_image'));
            }
            if($request->hasFile('second_image')) {
                $data['second_image'] = $this->saveImage($request->file('second_image'));
            }
            if($request->hasFile('third_image')) {
                $data['third_image'] = $this->saveImage($request->file('third_image'));
            }
            $data['slug'] = Str::slug($data['name']);
            $product = Product::create($data);

            $product->colors()->sync($request->color_ids);
            $product->sizes()->sync($request->size_ids);

            return redirect()->route('admin.products.index')->with('success', 'Product added successfully');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('colors');
        $product->load('sizes');

        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        $brands = Brand::all();

        return Inertia::render('admin/products/edit',[
            'colors' => $colors,
            'sizes' => $sizes,
            'categories' => $categories,
            'brands' => $brands,
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        if($request->validated()) {
            $data = $request->validated();

            $data['slug'] = Str::slug($request->name);
            $data['status'] = $request->status;

            $product->update($data);

            $product->colors()->sync($request->color_ids);

            $product->sizes()->sync($request->size_ids);

            return redirect()->route('admin.products.index')->with([
                'success' => 'Product has been updated successfully'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Product::removeProductImageFromStorage($product->thumbnail);
        Product::removeProductImageFromStorage($product->first_image);
        Product::removeProductImageFromStorage($product->second_image);
        Product::removeProductImageFromStorage($product->third_image);
        $product->delete();

        return redirect()->route('admin.products.index');
    }

    private function saveImage($image)
    {
        $image_name = time().'_'.$image->getClientOriginalName();
        $image->storeAs('images/products',$image_name,'public');
        return 'storage/images/products/'.$image_name;
    }


}
