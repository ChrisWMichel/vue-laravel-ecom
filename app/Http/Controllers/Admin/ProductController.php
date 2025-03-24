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
            
            // Log the thumbnail upload attempt
            Log::info("Attempting to save thumbnail: " . $request->file('thumbnail')->getClientOriginalName());
            
            try {
                $data['thumbnail'] = $this->saveImage($request->file('thumbnail'));
                $data['price'] = number_format((float)$data['price'], 2, '.', '');
    
                if($request->hasFile('first_image')) {
                    Log::info("Attempting to save first_image: " . $request->file('first_image')->getClientOriginalName());
                    $data['first_image'] = $this->saveImage($request->file('first_image'));
                }
                if($request->hasFile('second_image')) {
                    Log::info("Attempting to save second_image: " . $request->file('second_image')->getClientOriginalName());
                    $data['second_image'] = $this->saveImage($request->file('second_image'));
                }
                if($request->hasFile('third_image')) {
                    Log::info("Attempting to save third_image: " . $request->file('third_image')->getClientOriginalName());
                    $data['third_image'] = $this->saveImage($request->file('third_image'));
                }
                
                // Add slug to data
                $data['slug'] = Str::slug($data['name']);
                
                // Create the product
                $product = Product::create($data);
                
                // Sync relationships
                if (isset($data['color_ids'])) {
                    $product->colors()->sync($data['color_ids']);
                }
                
                if (isset($data['size_ids'])) {
                    $product->sizes()->sync($data['size_ids']);
                }
                
                return redirect()->route('admin.products.index')->with([
                    'success' => 'Product has been created successfully'
                ]);
                
            } catch (\Exception $e) {
                Log::error("Failed to save product images: " . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to upload images: ' . $e->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     */
   

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
        $path = 'images/products';
        $image_name = time().'_'.$image->getClientOriginalName();
        $fullPath = $path.'/'.$image_name;
        
        try {
            // Try a different approach to upload the file
            $fileContents = file_get_contents($image->getRealPath());
            
            // Get the S3 client directly to access more detailed error information
            $s3Client = Storage::disk('s3')->getClient();
            
            try {
                // Use the S3 client directly
                $result = $s3Client->putObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key'    => $fullPath,
                    'Body'   => $fileContents,
                    'ContentType' => $image->getMimeType()
                ]);
                
                Log::info("ImageController: S3 put successful. RequestId: " . $result['RequestId']);
                
                // Return the absolute URL to the image
                $url = $result['ObjectURL'] ?? Storage::disk('s3')->url($fullPath);
                
                Log::info('ImageController: Generated S3 image URL: ' . $url);
                
                return $url;
            } catch (\Aws\S3\Exception\S3Exception $e) {
                // This will catch specific AWS S3 exceptions
                Log::error("ImageController: AWS S3 Exception: " . $e->getMessage());
                Log::error("ImageController: AWS Error Code: " . $e->getAwsErrorCode());
                Log::error("ImageController: AWS Error Type: " . $e->getAwsErrorType());
                Log::error("ImageController: AWS Request ID: " . $e->getAwsRequestId());
                Log::error("ImageController: AWS Extended Request ID: " . $e->getResponse()->getHeaderLine('x-amz-id-2'));
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error("ImageController: General exception: " . $e->getMessage());
            Log::error("ImageController: " . $e->getTraceAsString());
            throw $e;
        }

        
    }
}



