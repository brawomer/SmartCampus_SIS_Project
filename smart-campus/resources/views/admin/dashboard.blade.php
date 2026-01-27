<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-bold text-2xl mb-6">Campus Maintenance Overview</h2>

            <div class="grid grid-cols-3 gap-6 mb-8">
                <div class="bg-red-100 p-6 rounded-lg shadow">
                    <p class="text-red-600 font-bold">Pending Repairs</p>
                    <h4 class="text-3xl font-black">{{ $stats['pending'] }}</h4>
                </div>
                <div class="bg-green-100 p-6 rounded-lg shadow">
                    <p class="text-green-600 font-bold">Completed Repairs</p>
                    <h4 class="text-3xl font-black">{{ $stats['fixed'] }}</h4>
                </div>
                <div class="bg-blue-100 p-6 rounded-lg shadow">
                    <p class="text-blue-600 font-bold">Total Lifecycle Items</p>
                    <h4 class="text-3xl font-black">{{ $stats['total'] }}</h4>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold mb-4">Recent Mobile Reports</h3>
                <table class="w-full text-left">
                    <tr class="border-b">
                        <th class="py-2">Item</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Date</th>
                    </tr>
                    @foreach($latestReports as $report)
                    <tr class="border-b">
                        <td class="py-2">{{ $report->item_name }}</td>
                        <td class="py-2">
                            <span class="px-2 py-1 rounded text-xs {{ $report->status == 'fixed' ? 'bg-green-200' : 'bg-red-200' }}">
                                {{ strtoupper($report->status) }}
                            </span>
                        </td>
                        <td class="py-2">{{ $report->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
