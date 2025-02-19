<?php
namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
 
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
            return response()->json(['success' => 'Review updated successfully.'], 200);
            
        } else{
            return response()->json(['error' => 'Something went wrong, try again later.'], 400);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $review = $this->ValidateUserReview($request);
        if ($review) {
            $review->delete();
            return response()->json(['success' => 'Review deleted successfully.'], 200);
        } else{
            return response()->json(['error' => 'Something went wrong, try again later.'], 400);
        }
    }
    /**
     * Check if the user has already reviewed the product.
     *
     * @param Request $request
     */
    private function ValidateUserReview(Request $request)
    {
      return Review::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->exists();
    }
}

