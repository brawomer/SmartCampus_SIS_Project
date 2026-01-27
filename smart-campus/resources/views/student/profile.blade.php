<x-app-layout>
    <div class="p-6">
        <h2 class="text-2xl font-bold">My Academic Record</h2>

        <div class="mt-4 bg-blue-50 p-4 rounded">
            <strong>Total Classes Attended:</strong> {{ auth()->user()->attendances->count() }}
        </div>

        <div class="mt-6">
            <h3 class="font-bold">My Grades</h3>
            @foreach(auth()->user()->grades as $grade)
                <div class="border-b py-2">
                    {{ $grade->subject }}: <span class="font-bold">{{ $grade->score }}%</span>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
