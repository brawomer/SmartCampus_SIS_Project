@props(['header' => null])

<x-layouts::app.sidebar :title="$header">
    <flux:main>
        @if($header)
            <div class="px-4 py-6 sm:px-0">
                {{ $header }}
            </div>
        @endif
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
