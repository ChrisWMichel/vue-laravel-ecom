<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ImageController extends Controller
{
    public function deleteImage(Product $product, $imageField)
    {
        if ($product->$imageField) {
            Product::removeProductImageFromStorage($product->$imageField);
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

            Product::removeProductImageFromStorage($product->$imageField);


        if ($request->hasFile($imageField)) {
            // Save new image
            $product->$imageField = $this->saveImage($request->file($imageField));
            $product->save();
        }

        return redirect()->route('admin.products.edit', $product)->with('success', ucfirst($imageField) . ' updated successfully');
    }

    private function saveImage($image)
    {
        $image_name = time().'_'.$image->getClientOriginalName();
        $image->storeAs('images/products',$image_name,'public');
        return 'storage/images/products/'.$image_name;
    }

}
