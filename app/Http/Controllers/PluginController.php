<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class PluginController extends Controller
{
    /**
     * Show a public preview page for a live plugin, together with every
     * review shoppers have left on it.
     */
    public function show(Plugin $plugin): View
    {
        abort_unless($plugin->is_live, 404);

        $reviews = $plugin->reviews()
            ->with('user')
            ->latest()
            ->get();

        $related = Plugin::query()
            ->live()
            ->where('id', '!=', $plugin->id)
            ->orderByRaw('CASE WHEN category = ? THEN 0 ELSE 1 END', [$plugin->category])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(3)
            ->get();

        return view('plugins.show', [
            'plugin' => $plugin,
            'related' => $related,
            'reviews' => $reviews,
            'stats' => $this->ratingSummary($reviews),
        ]);
    }

    /**
     * The headline numbers behind the ratings block: the average stars, how
     * many reviews there are and how many land in each star band. Counted
     * from the reviews being shown so the bars always add up to the list.
     *
     * @param  Collection<int, Review>  $reviews
     * @return array{average: string, total: int, bands: list<array{stars: int, count: int, width: int}>}
     */
    private function ratingSummary(Collection $reviews): array
    {
        $counts = array_fill_keys([5, 4, 3, 2, 1], 0);

        foreach ($reviews as $review) {
            $counts[$review->rating] = ($counts[$review->rating] ?? 0) + 1;
        }

        $total = $reviews->count();
        $bands = [];

        foreach ([5, 4, 3, 2, 1] as $stars) {
            $count = $counts[$stars];

            $bands[] = [
                'stars' => $stars,
                'count' => $count,
                // Bars step in 5% increments, matching the w-* width utilities.
                'width' => $total > 0 ? (int) round($count / $total * 20) * 5 : 0,
            ];
        }

        return [
            'average' => number_format($reviews->avg('rating') ?? 0, 1),
            'total' => $total,
            'bands' => $bands,
        ];
    }
}
