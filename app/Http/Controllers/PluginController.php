<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use Illuminate\View\View;

class PluginController extends Controller
{
    /**
     * Show a public preview page for a live plugin.
     */
    public function show(Plugin $plugin): View
    {
        abort_unless($plugin->is_live, 404);

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
        ]);
    }
}
