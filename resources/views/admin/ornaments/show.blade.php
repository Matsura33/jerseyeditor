<x-layouts.admin>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ornament Details') }}
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
                        <h3 class="text-lg font-semibold mb-4">Ornament Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">Name:</p>
                                <p class="font-semibold">{{ $ornament->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Status:</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $ornament->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $ornament->active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-gray-600">Description:</p>
                                <p class="mt-1">{{ $ornament->description }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-gray-600 mb-2">Current Image:</p>
                                @if($ornament->image)
                                    <img src="{{ Storage::url($ornament->image) }}" alt="Current image" class="h-64 w-64 object-contain">
                                @else
                                    <p class="text-gray-500">No image uploaded</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Versions</h3>
                            <form action="{{ route('admin.ornaments.versions.store', $ornament) }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4">
                                @csrf
                                <div>
                                    <input type="text" name="name" placeholder="Version name" required class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <input type="file" name="image" required class="mr-2">
                                </div>
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Add Version
                                </button>
                            </form>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created At</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ornament->versions as $version)
                                        <tr>
                                            <td class="px-6 py-4 border-b border-gray-300">{{ $version->name }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300">
                                                <img src="{{ Storage::url($version->image_url) }}" alt="Version image" class="h-16 w-16 object-contain">
                                            </td>
                                            <td class="px-6 py-4 border-b border-gray-300">{{ $version->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td class="px-6 py-4 border-b border-gray-300">
                                                <form action="{{ route('admin.ornaments.versions.destroy', [$ornament, $version]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this version?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 border-b border-gray-300 text-center">No versions found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('admin.ornaments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Back to List
                        </a>
                        <a href="{{ route('admin.ornaments.edit', $ornament) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Edit Ornament
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin> 