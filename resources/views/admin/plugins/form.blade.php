@php
    $editing = $plugin->exists;
    $gallery = old('gallery', $plugin->gallery ?? []);
    $selectedHosts = old('hosts', $plugin->hosts ?? []);
@endphp

<x-app-layout>
    <div class="app">
        @include('layouts.admin-nav')

        <div class="app-main">
            <header class="top">
                <div>
                    <h1>{{ $editing ? 'Edit plugin' : 'Upload plugin' }}</h1>
                    <div class="sub">
                        {{ $editing ? $plugin->name : 'Add a new plugin to the store.' }}
                    </div>
                </div>
            </header>

            <main class="content" id="content">
                <form
                    action="{{ $editing ? route('admin.plugins.update', $plugin) : route('admin.plugins.store') }}"
                    class="form-layout"
                    enctype="multipart/form-data"
                    id="pluginForm"
                    method="post"
                >
                    @csrf
                    @if ($editing)
                        @method('PUT')
                    @endif

                    <div class="form-main">
                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Basics</h2>
                                    <p>What customers see first.</p>
                                </div>
                            </div>
                            <div class="form">
                                <div class="two">
                                    <div class="field">
                                        <label for="name">Plugin name</label>
                                        <input
                                            id="name"
                                            maxlength="60"
                                            name="name"
                                            placeholder="e.g. Squash Stretch Pro"
                                            required
                                            value="{{ old('name', $plugin->name) }}"
                                        />
                                        @error('name')
                                            <span class="ferr">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="field">
                                        <label for="tagline">One-line pitch</label>
                                        <input
                                            id="tagline"
                                            maxlength="90"
                                            name="tagline"
                                            placeholder="What it does in one sentence"
                                            value="{{ old('tagline', $plugin->tagline) }}"
                                        />
                                        @error('tagline')
                                            <span class="ferr">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="field">
                                    <label for="description">Description</label>
                                    <textarea
                                        id="description"
                                        maxlength="2000"
                                        name="description"
                                        placeholder="What it does, who it's for and how it works."
                                    >{{ old('description', $plugin->description) }}</textarea>
                                    @error('description')
                                        <span class="ferr">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="field">
                                    <label for="features">Key features</label>
                                    <textarea
                                        class="min-h-90"
                                        id="features"
                                        maxlength="800"
                                        name="features"
                                        placeholder="One feature per line"
                                    >{{ old('features', $plugin->features) }}</textarea>
                                    @error('features')
                                        <span class="ferr">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="field">
                                    <label for="youtube_url">YouTube tutorial link</label>
                                    <input
                                        id="youtube_url"
                                        name="youtube_url"
                                        placeholder="https://www.youtube.com/watch?v=..."
                                        type="url"
                                        value="{{ old('youtube_url', $plugin->youtube_url) }}"
                                    />
                                    <small>Customers see a "Watch tutorial" button on the plugin page.</small>
                                    @error('youtube_url')
                                        <span class="ferr">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Cover image</h2>
                                    <p>Shown on the shelf and at the top of the plugin page. Landscape works best.</p>
                                </div>
                            </div>
                            @if ($plugin->cover_image)
                                <img
                                    alt="Current cover"
                                    class="cover-preview"
                                    src="{{ asset('storage/'.$plugin->cover_image) }}"
                                />
                            @endif
                            <div class="field">
                                <label for="cover">Choose cover image</label>
                                <input
                                    accept="image/png,image/jpeg,image/webp,image/gif"
                                    data-plugin-image
                                    id="cover"
                                    name="cover"
                                    type="file"
                                />
                                <small>PNG, JPG, WebP or GIF, up to 4 MB.</small>
                                @error('cover')
                                    <span class="ferr">{{ $message }}</span>
                                @enderror
                            </div>
                        </section>

                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Extra images <span class="badge plain">Optional, up to 4</span></h2>
                                    <p>Show the plugin in action. Each image can have a title and explanation.</p>
                                </div>
                            </div>
                            <div class="shot-slots">
                                @foreach (range(0, 3) as $index)
                                    @php($image = $gallery[$index] ?? [])
                                    <div class="slot">
                                        @if (! empty($image['path']))
                                            <img
                                                alt="Current image {{ $index + 1 }}"
                                                class="cover-preview"
                                                src="{{ asset('storage/'.$image['path']) }}"
                                            />
                                        @endif
                                        <div class="field">
                                            <label for="images_{{ $index }}">Image {{ $index + 1 }}</label>
                                            <input
                                                accept="image/png,image/jpeg,image/webp,image/gif"
                                                data-plugin-image
                                                id="images_{{ $index }}"
                                                name="images[{{ $index }}]"
                                                type="file"
                                            />
                                            @error('images.'.$index)
                                                <span class="ferr">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <input
                                            aria-label="Image {{ $index + 1 }} title"
                                            maxlength="60"
                                            name="image_titles[{{ $index }}]"
                                            placeholder="Title, e.g. One-click setup"
                                            value="{{ old('image_titles.'.$index, $image['title'] ?? '') }}"
                                        />
                                        @error('image_titles.'.$index)
                                            <span class="ferr">{{ $message }}</span>
                                        @enderror
                                        <textarea
                                            aria-label="Image {{ $index + 1 }} details"
                                            maxlength="600"
                                            name="image_texts[{{ $index }}]"
                                            placeholder="Explain what this image shows"
                                        >{{ old('image_texts.'.$index, $image['text'] ?? '') }}</textarea>
                                        @error('image_texts.'.$index)
                                            <span class="ferr">{{ $message }}</span>
                                        @enderror
                                        @if (! empty($image['path']))
                                            <label class="toggle">
                                                <input name="remove_images[]" type="checkbox" value="{{ $index }}" />
                                                Remove this image
                                            </label>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    </div>

                    <aside class="form-side">
                        <section class="panel">
                            <div class="panel-h">
                                <div><h2>Publish</h2></div>
                            </div>
                            <div class="form">
                                <label class="toggle">
                                    <input
                                        @checked(old('is_live', $plugin->is_live))
                                        name="is_live"
                                        type="checkbox"
                                        value="1"
                                    />
                                    Show in the store
                                </label>
                                <button class="btn" type="submit">
                                    {{ $editing ? 'Save changes' : 'Create plugin' }}
                                </button>
                                <a class="btn ghost" href="{{ route('admin.plugins') }}">Cancel</a>
                            </div>
                        </section>

                        <section class="panel">
                            <div class="panel-h">
                                <div><h2>Pricing</h2></div>
                            </div>
                            <div class="form">
                                <div class="field">
                                    <label for="price">Price (USD)</label>
                                    <input
                                        id="price"
                                        min="0"
                                        name="price"
                                        placeholder="0 for free"
                                        required
                                        step="0.01"
                                        type="number"
                                        value="{{ old('price', $plugin->price ?? '0') }}"
                                    />
                                    @error('price')
                                        <span class="ferr">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="field">
                                    <label for="old_price">Old price <small>(optional, shows a sale)</small></label>
                                    <input
                                        id="old_price"
                                        min="0"
                                        name="old_price"
                                        placeholder="Leave empty if no sale"
                                        step="0.01"
                                        type="number"
                                        value="{{ old('old_price', $plugin->old_price) }}"
                                    />
                                    @error('old_price')
                                        <span class="ferr">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        <section class="panel">
                            <div class="panel-h">
                                <div><h2>Details</h2></div>
                            </div>
                            <div class="form">
                                <div class="field">
                                    <label for="category">Category</label>
                                    <select id="category" name="category">
                                        @foreach (\App\Models\Plugin::CATEGORIES as $category)
                                            <option @selected(old('category', $plugin->category) === $category)>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <span class="ferr">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="field">
                                    <label for="badge">Badge</label>
                                    <select id="badge" name="badge">
                                        <option value="">None</option>
                                        @foreach (\App\Models\Plugin::BADGES as $badge)
                                            <option @selected(old('badge', $plugin->badge) === $badge) value="{{ $badge }}">
                                                {{ $badge }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('badge')
                                        <span class="ferr">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="field">
                                    <span class="lab">Works with</span>
                                    <div class="chips">
                                        @foreach (\App\Models\Plugin::HOSTS as $code => $label)
                                            <label>
                                                <input
                                                    @checked(in_array($code, (array) $selectedHosts, true))
                                                    name="hosts[]"
                                                    type="checkbox"
                                                    value="{{ $code }}"
                                                />
                                                {{ $label }}
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('hosts')
                                        <span class="ferr">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="field">
                                    <label for="version">Version</label>
                                    <input
                                        id="version"
                                        maxlength="20"
                                        name="version"
                                        placeholder="e.g. 1.0.0"
                                        value="{{ old('version', $plugin->version) }}"
                                    />
                                    @error('version')
                                        <span class="ferr">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="field">
                                    <label for="requirements">Requirements</label>
                                    <input
                                        id="requirements"
                                        maxlength="60"
                                        name="requirements"
                                        placeholder="e.g. AE 2024+, Win & macOS"
                                        value="{{ old('requirements', $plugin->requirements) }}"
                                    />
                                    @error('requirements')
                                        <span class="ferr">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        <section class="panel">
                            <div class="panel-h">
                                <div>
                                    <h2>Download</h2>
                                    <p>Given to customers after they buy, by email and in their dashboard.</p>
                                </div>
                            </div>
                            <div class="field">
                                <label for="download_url">Download link</label>
                                <input
                                    id="download_url"
                                    name="download_url"
                                    placeholder="Google Drive, Dropbox…"
                                    type="url"
                                    value="{{ old('download_url', $plugin->download_url) }}"
                                />
                                @error('download_url')
                                    <span class="ferr">{{ $message }}</span>
                                @enderror
                            </div>
                        </section>
                    </aside>
                </form>
            </main>
        </div>
    </div>

        @push('scripts')
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    var maxBytes = 4 * 1024 * 1024;

                    document.querySelectorAll("[data-plugin-image]").forEach(function (input) {
                        input.addEventListener("change", function () {
                            var file = input.files && input.files[0];

                            if (!file || file.size <= maxBytes) return;

                            input.value = "";

                            if (window.Toon && typeof window.Toon.toast === "function") {
                                window.Toon.toast(file.name + " is larger than 4 MB. Choose a smaller image.");
                            }
                        });
                    });
                });
            </script>
        @endpush
    </x-app-layout>
