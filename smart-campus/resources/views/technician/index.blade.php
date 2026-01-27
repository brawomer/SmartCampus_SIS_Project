<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-bold text-2xl mb-4">Pending Repairs (Mobile Scans)</h2>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Item</th>
                            <th class="py-2">Problem</th>
                            <th class="py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr class="border-b">
                            <td class="py-2">{{ $report->item_name }}</td>
                            <td class="py-2">{{ $report->description }}</td>
                            <td class="py-2">
                                <form action="{{ route('reports.fix', $report->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="bg-green-600 text-white px-3 py-1 rounded">Mark Fixed</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
