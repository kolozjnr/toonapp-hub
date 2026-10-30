@php
    $flashErrors = collect();

    if (session('error')) {
        $flashErrors->push(session('error'));
    }

    if (session('warning')) {
        $flashErrors->push(session('warning'));
    }

    foreach ($errors->getBags() as $bag) {
        foreach ($bag->all() as $error) {
            $flashErrors->push($error);
        }
    }

    $successMessage = session('success');
    $toastMessage = session('toast') ?: $successMessage;
@endphp

@if ($flashErrors->isNotEmpty())
    <div class="flash-stack" role="alert" aria-live="assertive">
        <div class="flash flash-error">
            <div class="flash-body">
                <strong>{{ $flashErrors->count() === 1 ? 'Something needs your attention' : 'Please fix the following' }}</strong>
                <ul>
                    @foreach ($flashErrors as $flashError)
                        <li>{{ $flashError }}</li>
                    @endforeach
                </ul>
            </div>
            <button
                aria-label="Dismiss message"
                class="flash-close"
                data-flash-close
                type="button"
            >&times;</button>
        </div>
    </div>

    <script>
        document.addEventListener("click", function (event) {
            var button = event.target.closest("[data-flash-close]");

            if (!button) return;

            var flash = button.closest(".flash");
            if (flash) flash.remove();

            var stack = document.querySelector(".flash-stack");
            if (stack && !stack.querySelector(".flash")) stack.remove();
        });
    </script>
@endif

@if ($successMessage)
    <span class="visually-hidden" role="status">{{ $successMessage }}</span>
@endif

@if ($toastMessage)
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var message = @json($toastMessage);

            if (window.Toon && typeof window.Toon.toast === "function") {
                window.Toon.toast(message);
            }
        });
    </script>
@endif
