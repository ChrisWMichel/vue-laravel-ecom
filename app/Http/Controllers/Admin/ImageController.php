<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function deleteImage(Product $product, $imageField)
    {
        if ($product->$imageField) {
            $path = parse_url($product->$imageField, PHP_URL_PATH);
            $path = ltrim($path, '/');
            
            // Delete from S3
            if (Storage::disk('s3')->exists($path)) {
                Storage::disk('s3')->delete($path);
            }
            
            $product->$imageField = null;
            $product->save();
        }

        //return response()->json(['message' => ucfirst($imageField) . ' deleted successfully']);
    }

    public function updateImage(Request $request, Product $product, $imageField)
    {
        $request->validate([
            $imageField => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
    
        if ($product->$imageField) {
            // Extract the path from the full URL
            $path = parse_url($product->$imageField, PHP_URL_PATH);
            $path = ltrim($path, '/');
            
            // Delete from S3
            if (Storage::disk('s3')->exists($path)) {
                Storage::disk('s3')->delete($path);
            }
        }
    
        if ($request->hasFile($imageField)) {
            // Save new image and log the process
            Log::info("Saving {$imageField} image: " . $request->file($imageField)->getClientOriginalName());
            
            try {
                $product->$imageField = $this->saveImage($request->file($imageField));
                $product->save();
                
                Log::info("Successfully saved {$imageField} with URL: " . $product->$imageField);
            } catch (\Exception $e) {
                Log::error("Failed to save {$imageField} image: " . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage());
            }
        }
    
        return redirect()->route('admin.products.edit', $product)->with('success', ucfirst($imageField) . ' updated successfully');
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
