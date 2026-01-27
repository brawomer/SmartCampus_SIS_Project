
<x-layouts::app :title="__('Dashboard')">
    <div class="mt-6 p-6 bg-black shadow-sm rounded-lg">
    <h3 class="font-bold text-lg mb-4">Report a Broken Item</h3>

    <form action="{{ route('reports.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700">What is broken?</label>
            <input type="text" name="item_name" placeholder="e.g. Lab 2 Projector" class="w-full border-gray-300 rounded-md" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Describe the problem</label>
            <textarea name="description" placeholder="Explain what is wrong..." class="w-full border-gray-300 rounded-md" required></textarea>
        </div>
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Submit Report</button>
    </form>
</div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts::app>
