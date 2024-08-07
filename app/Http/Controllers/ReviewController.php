<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Models\Order;

class ReviewController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'ratings' => 'required|integer|min:1|max:5',
            'comments' => 'required|string|max:1000',
            'uploads.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $review = new Review();
        $review->customer_id = auth()->id();
        $review->product_id = $productId;
        $review->ratings = $request->ratings;
        $review->comments = $request->comments;

        if ($request->hasFile('uploads')) {
            $imagePaths = [];
            foreach ($request->file('uploads') as $file) {
                $fileName = time() . '-' . $file->getClientOriginalName();
                $filePath = $file->storeAs('public/images', $fileName);
                $imagePaths[] = 'storage/images/' . $fileName;
            }
            $review->img_path = implode(',', $imagePaths);
        }
        $review->save();
        return redirect()->back()->with('success', 'Review submitted successfully!');
    }

    public function show($id)
    {
        $product = Product::with(['brand', 'reviews.customer'])->findOrFail($id);
        $canReview = false;

        if (auth()->check()) {
            $customerId = auth()->id();
            $hasOrdered = Order::where('customer_id', $customerId)
                ->whereHas('products', function ($query) use ($id) {
                    $query->where('product_id', $id);
                })->exists();

            if ($hasOrdered) {
                $canReview = true;
            }
        }
        return view('shop.show', compact('product', 'canReview'));
    }

    private function calculateRatingStars($reviews)
    {
        $totalRatings = $reviews->count();
        $totalSum = $reviews->sum('ratings');
        $totalStars = $totalRatings * 5;
        $percentage = $totalStars > 0 ? round(($totalSum / $totalStars) * 100) : 0;

        if ($totalRatings > 0) {
            if ($percentage >= 80) return 5;
            if ($percentage >= 60) return 4;
            if ($percentage >= 40) return 3;
            if ($percentage >= 20) return 2;
            return 1;
        }
        return 0;
    }

    private function calculateRatingPercentage($reviews)
    {
        $totalRatings = $reviews->count();
        $totalSum = $reviews->sum('ratings');
        $totalStars = $totalRatings * 5;
        return $totalStars > 0 ? round(($totalSum / $totalStars) * 100) : 0;
    }




    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
