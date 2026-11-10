<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ReviewRequest;
use App\Models\Plugin;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * Save the shopper's rating for a plugin they own. A shopper gets one
     * review per plugin, so a second submit updates the stars they left.
     */
    public function store(ReviewRequest $request, Plugin $plugin): RedirectResponse
    {
        $comment = trim((string) $request->input('comment', ''));

        Review::query()->updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'plugin_id' => $plugin->id,
            ],
            [
                'rating' => (int) $request->input('rating'),
                'comment' => $comment === '' ? null : $comment,
            ],
        );

        return back()->with('success', "Thanks! Your {$plugin->name} review is live.");
    }
}
