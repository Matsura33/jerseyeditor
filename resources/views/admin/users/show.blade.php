<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">User Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">Name:</p>
                                <p class="font-semibold">{{ $user->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Email:</p>
                                <p class="font-semibold">{{ $user->email }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Created At:</p>
                                <p class="font-semibold">{{ $user->created_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Last Updated:</p>
                                <p class="font-semibold">{{ $user->updated_at->format('Y-m-d H:i:s') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">User Jerseys</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jersey</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($user->userJerseys as $userJersey)
                                        <tr>
                                            <td class="px-6 py-4 border-b border-gray-300">{{ $userJersey->jersey->name }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $userJersey->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $userJersey->active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 border-b border-gray-300">{{ $userJersey->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 border-b border-gray-300 text-center">No jerseys found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Back to List
                        </a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Edit User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 