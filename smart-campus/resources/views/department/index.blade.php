
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-800 leading-tight">
            {{ auth()->user()->department }} Department Staff
            @if(session('success'))
         <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
         {{ session('success') }}
          </div>
            @endif
        </h2>
    </x-slot>
    <div class="mb-6 p-4 bg-black rounded-lg border">
    <h3 class="font-bold mb-3">Add New Student to {{ auth()->user()->department }}</h3>
    <form action="{{ route('department.addStudent') }}" method="POST" class="flex gap-4">
        @csrf
        <input type="text" name="name" placeholder="Student Name" class="border p-2 rounded w-full" required>
        <input type="email" name="email" placeholder="Email Address" class="border p-2 rounded w-full" required>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow">Add Student</button>
    </form>
</div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-black overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Name</th>
                            <th class="py-2">Role</th>
                            <th class="py-2">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staff as $member)
                        <tr class="border-b">
                            <td class="py-2">{{ $member->name }}</td>
                            <td class="py-2">{{ ucfirst($member->role) }}</td>
                            <td class="py-2">{{ $member->email }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
