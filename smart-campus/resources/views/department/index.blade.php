<x-app-layout>
    <div class="py-12 bg-zinc-950 min-h-screen text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="text-2xl font-bold mb-6 text-blue-500">
                {{ auth()->user()->department }} Department Staff
            </h2>

            <div class="mb-6 p-6 bg-zinc-900 rounded-xl border border-zinc-800 shadow-xl">
                <h3 class="font-bold mb-4 text-zinc-300">Add New Student to {{ auth()->user()->department }}</h3>
                <form action="{{ route('department.addStudent') }}" method="POST" class="flex flex-wrap gap-4">
                    @csrf
                    <input type="text" name="name" placeholder="Student Name" class="bg-zinc-800 border-zinc-700 text-white p-2 rounded-md flex-1" required>
                    <input type="email" name="email" placeholder="Email Address" class="bg-zinc-800 border-zinc-700 text-white p-2 rounded-md flex-1" required>
                    <input type="password" name="password" placeholder="Student password" class="bg-zinc-800 border-zinc-700 text-white p-2 rounded-md flex-1" required>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-md font-bold transition">
                        Add Student
                    </button>
                </form>
            </div>

            <div class="bg-zinc-900 overflow-hidden shadow-xl rounded-xl border border-zinc-800 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-zinc-300">Registered Members</h3>
                    <a href="{{ route('department.export') }}" class="text-sm bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-500 transition">
                        Download Excel
                    </a>
                </div>

                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-zinc-800 text-zinc-500 text-sm uppercase">
                            <th class="py-3 px-2">Name</th>
                            <th class="py-3 px-2">Role</th>
                            <th class="py-3 px-2">Email</th>
                            <th class="py-3 px-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        @foreach($staff as $member)
                        <tr class="hover:bg-zinc-800/50 transition">
                            <td class="py-4 px-2">{{ $member->name }}</td>
                            <td class="py-4 px-2">
                                <span class="px-2 py-1 rounded text-xs {{ $member->role === 'staff' ? 'bg-blue-900 text-blue-200' : 'bg-zinc-700 text-zinc-300' }}">
                                    {{ ucfirst($member->role) }}
                                </span>
                            </td>
                            <td class="py-4 px-2 text-zinc-400">{{ $member->email }}</td>
                            <td class="py-4 px-2 text-right">
                                @if($member->role === 'student')
                                    <form action="{{ route('department.deleteStudent', $member->id) }}" method="POST" onsubmit="return confirm('Remove this student?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-400 text-sm font-semibold">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
