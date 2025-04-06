<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Jersey Details') }}
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
                        <h3 class="text-lg font-semibold mb-4">User Jersey Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">User:</p>
                                <p class="font-semibold">{{ $userJersey->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Base Jersey:</p>
                                <p class="font-semibold">{{ $userJersey->jersey->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Resize Value:</p>
                                <p class="font-semibold">{{ $userJersey->resize_value }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Status:</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $userJersey->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $userJersey->active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-gray-600 mb-2">Texture Image:</p>
                                @if($userJersey->texture_url)
                                    <img src="{{ Storage::url($userJersey->texture_url) }}" alt="Texture image" class="h-64 w-64 object-contain">
                                @else
                                    <p class="text-gray-500">No texture image uploaded</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Ornaments</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Version</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Position X</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Position Y</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($userJersey->ornaments as $ornament)
                                        <tr>
                                            <td class="px-6 py-4 border-b border-gray-300">{{ $ornament->name }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300">{{ $ornament->versions->where('id', $ornament->pivot->ornament_version_id)->first()->name }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300">{{ $ornament->pivot->position_x }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300">{{ $ornament->pivot->position_y }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 border-b border-gray-300 text-center">No ornaments found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('user-jerseys.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Back to List
                        </a>
                        <a href="{{ route('user-jerseys.edit', $userJersey) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Edit User Jersey
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 