<?php
namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('product', 'user')->where('approved', 0)->latest()->get();
        $totalCount = Review::where('approved', 0)->count();

        return Inertia::render('admin/reviews/index', [
            'reviews' => $reviews,
            'totalCount' => $totalCount
        ]);
    }
 
    public function toggleApproveStatus(Review $review,$status)
    {
        $review->update([
            'approved' => $status
        ]);
        
        $reviews = Review::with('product', 'user')->where('approved', 0)->latest()->get();
        $totalCount = Review::where('approved', 0)->count();

        return response()->json([
            'success' => 'Review has been updated successfully',
            'reviews' => $reviews,
            'totalCount' => $totalCount
        ]);
        
    }

    public function delete(Review $review)
    {
        $review->delete();

        $reviews = Review::with('product', 'user')->where('approved', 0)->latest()->get();
        $totalCount = Review::where('approved', 0)->count();

        return Inertia::render('admin/reviews/index')->with([
            'success' => 'Review has been deleted successfully',
            'reviews' => $reviews,
            'totalCount' => $totalCount
        ]);
        
    }
}

