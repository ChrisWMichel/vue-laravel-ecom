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
        try {
            $path = parse_url($product->$imageField, PHP_URL_PATH);
            $path = ltrim($path, '/');
            
            // Delete from S3 with error handling
            try {
                if (Storage::disk('s3')->exists($path)) {
                    Storage::disk('s3')->delete($path);
                }
            } catch (\Exception $e) {
                Log::warning("Failed to delete S3 image but continuing: " . $e->getMessage());
                // Continue even if S3 delete fails
            }
            
            $product->$imageField = null;
            $product->save();
            
            return response()->json(['message' => ucfirst($imageField) . ' deleted successfully']);
        } catch (\Exception $e) {
            Log::error("Error in deleteImage: " . $e->getMessage());
            return response()->json(['message' => 'Error deleting image: ' . $e->getMessage()], 500);
        }
    }
    
    //return response()->json(['message' => 'No image to delete']);
}

    public function updateImage(Request $request, Product $product, $imageField)
    {
        $request->validate([
            $imageField => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
    
        if ($product->$imageField) {
            try {
                // Extract the path from the full URL
                $path = parse_url($product->$imageField, PHP_URL_PATH);
                $path = ltrim($path, '/');
                
                // Delete from S3 - with error handling
                try {
                    if (Storage::disk('s3')->exists($path)) {
                        Storage::disk('s3')->delete($path);
                    }
                } catch (\Exception $e) {
                    Log::warning("Failed to delete previous image, but continuing: " . $e->getMessage());
                    // Continue with upload even if delete fails
                }
            } catch (\Exception $e) {
                Log::warning("Error parsing previous image URL: " . $e->getMessage());
                // Continue with upload even if URL parsing fails
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
        
        // Get bucket name from environment
        $bucket = env('AWS_BUCKET');
        
        // Log bucket name to verify it's set
        Log::info("Using S3 bucket: " . $bucket);
        
        if (empty($bucket)) {
            Log::error("AWS_BUCKET environment variable is not set!");
            throw new \Exception("AWS bucket name is not configured");
        }
        
        try {
            // Try a different approach to upload the file
            $fileContents = file_get_contents($image->getRealPath());
            
            // Get the S3 client directly to access more detailed error information
            $s3Client = Storage::disk('s3')->getClient();
            
            try {
                // Use the S3 client directly with explicit bucket name
                $result = $s3Client->putObject([
                    'Bucket' => $bucket,
                    'Key'    => $fullPath,
                    'Body'   => $fileContents,
                    'ContentType' => $image->getMimeType()
                ]);
                
                Log::info("S3 put successful. RequestId: " . ($result['RequestId'] ?? 'N/A'));
                
                // Construct the URL manually
                $url = "https://{$bucket}.s3." . env('AWS_DEFAULT_REGION') . ".amazonaws.com/{$fullPath}";
                
                Log::info('Generated S3 image URL: ' . $url);
                
                return $url;
            } catch (\Aws\S3\Exception\S3Exception $e) {
                // This will catch specific AWS S3 exceptions
                Log::error("AWS S3 Exception: " . $e->getMessage());
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error("General exception: " . $e->getMessage());
            throw $e;
        }
    }

}
