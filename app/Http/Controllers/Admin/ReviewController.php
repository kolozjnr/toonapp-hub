<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Every review shoppers have left, newest first, with the headline
     * numbers above the table.
     */
    public function index(): View
    {
        return view('admin.reviews', [
            'reviews' => Review::query()
                ->with(['plugin', 'user'])
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'stats' => $this->stats(),
        ]);
    }

    /**
     * Take a review off the list.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Review removed.');
    }

    /**
     * The average rating, how many reviews there are and how many of them
     * carry a comment, counted over every review rather than the page shown.
     *
     * @return array{average: string, total: int, commented: int}
     */
    private function stats(): array
    {
        $totals = Review::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('COALESCE(AVG(rating), 0) as average')
            ->selectRaw('COUNT(comment) as commented')
            ->toBase()
            ->first();

        return [
            'average' => number_format((float) $totals->average, 1),
            'total' => (int) $totals->total,
            'commented' => (int) $totals->commented,
        ];
    }
}
