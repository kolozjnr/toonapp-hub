<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * The number of plugins shown on the shelf before more are loaded.
     */
    public const PER_PAGE = 12;

    /**
     * The sort options supported by the store shelf.
     *
     * @var list<string>
     */
    private const SORTS = ['new', 'top', 'low', 'high'];

    /**
     * Show the storefront with the first page of live plugins.
     */
    public function index(Request $request): View
    {
        $filters = $this->filters($request);

        // The storefront always starts at the first page. The feed endpoint
        // handles the next pages as the visitor scrolls.
        $plugins = $this->query($filters)->paginate(self::PER_PAGE, ['*'], 'page', 1);

        return view('index', [
            'plugins' => $plugins,
            'categories' => $this->categories(),
            'filters' => $filters,
            'allTotal' => $this->liveTotal(),
            'feedUrl' => route('plugins.feed'),
        ]);
    }

    /**
     * Return a page of live plugins as JSON for the infinite scroll feed.
     */
    public function feed(Request $request): JsonResponse
    {
        $filters = $this->filters($request);
        $paginator = $this->query($filters)->paginate(self::PER_PAGE);

        return response()
            ->json([
                'data' => collect($paginator->items())
                    ->map(fn (Plugin $plugin) => $this->cardData($plugin))
                    ->values(),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                    'has_more' => $paginator->hasMorePages(),
                    'all_total' => $this->liveTotal(),
                ],
            ])
            ->header('Cache-Control', 'no-store');
    }

    /**
     * Normalise the shelf filters from the request.
     *
     * @return array{q: string, category: string, sort: string}
     */
    private function filters(Request $request): array
    {
        $q = mb_substr(trim((string) $request->query('q', '')), 0, 100);
        $category = trim((string) $request->query('category', 'All')) ?: 'All';
        $sort = (string) $request->query('sort', 'new');

        if (! in_array($sort, self::SORTS, true)) {
            $sort = 'new';
        }

        return compact('q', 'category', 'sort');
    }

    /**
     * Build the live plugin query for the given filters.
     *
     * @param  array{q: string, category: string, sort: string}  $filters
     */
    private function query(array $filters): Builder
    {
        $query = Plugin::query()->live();

        if ($filters['category'] !== 'All') {
            $query->where('category', $filters['category']);
        }

        if ($filters['q'] !== '') {
            $search = '%'.$filters['q'].'%';

            $query->where(function (Builder $query) use ($search): void {
                $query->where('name', 'like', $search)
                    ->orWhere('tagline', 'like', $search)
                    ->orWhere('description', 'like', $search)
                    ->orWhere('category', 'like', $search)
                    ->orWhere('features', 'like', $search);
            });
        }

        return match ($filters['sort']) {
            'low' => $query->orderBy('price')->orderBy('name'),
            'high' => $query->orderByDesc('price')->orderBy('name'),
            // Ratings are not stored yet, so "top rated" falls back to the
            // newest plugins until a reviews table is added.
            default => $query->orderByDesc('created_at')->orderByDesc('id'),
        };
    }

    /**
     * The category chips available on the shelf.
     *
     * @return Collection<int, string>
     */
    private function categories()
    {
        return Plugin::query()
            ->live()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
    }

    /**
     * The number of live plugins across the whole store.
     */
    private function liveTotal(): int
    {
        return Plugin::query()->live()->count();
    }

    /**
     * The payload used by both the server-rendered card and the JSON feed.
     *
     * @return array<string, mixed>
     */
    private function cardData(Plugin $plugin): array
    {
        $hosts = $plugin->hostTags();

        return [
            'id' => $plugin->id,
            'name' => $plugin->name,
            'slug' => $plugin->slug,
            'tagline' => $plugin->tagline,
            'features' => $plugin->features,
            'category' => $plugin->category,
            'price' => (float) $plugin->price,
            'old_price' => $plugin->old_price !== null ? (float) $plugin->old_price : null,
            'price_label' => $plugin->priceLabel(),
            'old_price_label' => $plugin->oldPriceLabel(),
            'is_free' => (float) $plugin->price <= 0,
            'badge' => $plugin->badgeLabel(),
            'hosts' => $hosts,
            'host_names' => implode(', ', array_column($hosts, 'name')),
            'cover_url' => $plugin->coverUrl(),
            'initials' => $plugin->initials(),
            'hue' => $plugin->coverHue(),
            'has_tutorial' => filled($plugin->youtube_url),
            'url' => route('plugins.show', $plugin->slug),
        ];
    }
}
