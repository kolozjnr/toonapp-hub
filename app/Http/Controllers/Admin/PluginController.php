<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PluginRequest;
use App\Models\Plugin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PluginController extends Controller
{
    /**
     * The public disk where plugin artwork is stored.
     */
    private const DISK = 'public';

    /**
     * Show the plugin list.
     */
    public function index(): View
    {
        return view('admin.plugins', [
            'plugins' => Plugin::query()->with('creator')->latest()->paginate(15),
        ]);
    }

    /**
     * Show the form for uploading a new plugin.
     */
    public function create(): View
    {
        return view('admin.plugins.form', [
            'plugin' => new Plugin,
        ]);
    }

    /**
     * Store a newly uploaded plugin.
     */
    public function store(PluginRequest $request): RedirectResponse
    {
        $plugin = Plugin::create($this->attributes($request));

        return redirect()
            ->route('admin.plugins.edit', $plugin)
            ->with('success', 'Plugin created successfully.');
    }

    /**
     * Show the form for editing an existing plugin.
     */
    public function edit(Plugin $plugin): View
    {
        return view('admin.plugins.form', [
            'plugin' => $plugin->load('creator'),
        ]);
    }

    /**
     * Update an existing plugin.
     */
    public function update(PluginRequest $request, Plugin $plugin): RedirectResponse
    {
        $plugin->update($this->attributes($request, $plugin));

        return redirect()
            ->route('admin.plugins.edit', $plugin)
            ->with('success', 'Plugin updated successfully.');
    }

    /**
     * Build the plugin attributes from the validated request.
     *
     * @return array<string, mixed>
     */
    private function attributes(PluginRequest $request, ?Plugin $plugin = null): array
    {
        $data = Arr::except($request->safe()->all(), [
            'cover',
            'images',
            'image_titles',
            'image_texts',
            'remove_images',
        ]);

        $data['is_live'] = $request->boolean('is_live');
        $data['hosts'] = array_values((array) $request->input('hosts', []));
        $data['cover_image'] = $this->storeCover($request, $plugin?->cover_image);
        $data['gallery'] = $this->storeGallery($request, $plugin?->gallery ?? []);

        if ($plugin === null) {
            $data['created_by'] = $request->user()->id;
        }

        return $data;
    }

    /**
     * Store a new cover image, replacing the existing one when present.
     */
    private function storeCover(PluginRequest $request, ?string $existing): ?string
    {
        if (! $request->hasFile('cover')) {
            return $existing;
        }

        $path = $request->file('cover')->store('plugins/covers', self::DISK);
        $this->deleteImage($existing);

        return $path;
    }

    /**
     * Build the gallery payload, storing any newly uploaded screenshots.
     *
     * @param  array<int, array{path?: string|null, title?: string, text?: string}>  $existing
     * @return list<array{path: string|null, title: string, text: string}>
     */
    private function storeGallery(PluginRequest $request, array $existing): array
    {
        $files = (array) $request->file('images', []);
        $titles = (array) $request->input('image_titles', []);
        $texts = (array) $request->input('image_texts', []);
        $removed = array_map('intval', (array) $request->input('remove_images', []));
        $gallery = [];

        foreach (range(0, 3) as $index) {
            $path = $existing[$index]['path'] ?? null;

            if (in_array($index, $removed, true)) {
                $this->deleteImage($path);
                $path = null;
            }

            if (($files[$index] ?? null) instanceof UploadedFile) {
                $this->deleteImage($path);
                $path = $files[$index]->store('plugins/gallery', self::DISK);
            }

            $title = trim((string) ($titles[$index] ?? ''));
            $text = trim((string) ($texts[$index] ?? ''));

            if ($path !== null || $title !== '' || $text !== '') {
                $gallery[] = [
                    'path' => $path,
                    'title' => $title,
                    'text' => $text,
                ];
            }
        }

        return $gallery;
    }

    /**
     * Remove an image from the public disk if it is managed by the app.
     */
    private function deleteImage(?string $path): void
    {
        if ($path !== null && $path !== '') {
            Storage::disk(self::DISK)->delete($path);
        }
    }
}
