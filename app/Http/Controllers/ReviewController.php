<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function index($productId)
{
    $reviews = Review::with('user')
    ->where('product_id', $productId)
    ->where('approved', 1)
    ->get();
    
    return response()->json([
        'reviews' => $reviews,
    ]);
}
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
            $exists = $this->ValidateUserReview($request);
            if ($exists) {
                return redirect()->back()->with('error', 'You have already reviewed this product.');
            } else {
                Review::create([
                    'title' => $request->title,
                    'body' => $request->body,
                    'rating' => $request->rating,
                    'user_id' => $request->user_id,
                    'product_id' => $request->product_id,
                    'approved' => 0
                ]);
                return redirect()->back()->with('success', 'Review submitted successfully, and will be published soon.');
            }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $review = $review = $this->ValidateUserReview($request);
        if ($review) {
            $review->where('user_id', $request->user()->id)
                ->where('product_id', $request->product_id)
                ->update([
                    'title' => $request->title,
                    'body' => $request->body,
                    'rating' => $request->rating,
                    'approved' => 0
                ]);
            
            return redirect()->back()->with('success', 'Review updated successfully.');
            
        } else{
            
            return redirect()->back()->with('error', 'Something went wrong, try again later.');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $review = Review::where('id', $id)
        ->where('user_id', $request->user_id)
        ->first();
        //dd($review);
        if ($review) {
            $review->delete();
            return redirect()->back()->with('success', 'Review deleted successfully.');
        } else{
            return redirect()->back()->with('error', 'Something went wrong, try again later.');
        }
    }
    /**
     * Check if the user has already reviewed the product.
     *
     * @param Request $request
     */
    private function ValidateUserReview(Request $request)
    {
        return Review::where('user_id', $request->user_id)
        ->where('product_id', $request->product_id)
        ->first();

     
    }
}

