<x-layouts.admin>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Jersey') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.jerseys.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                            <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('name') }}" required>
                            @error('name')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                            <textarea name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="layer_base" class="block text-gray-700 text-sm font-bold mb-2">Base Image</label>
                            <input type="file" name="layer_base" id="layer_base" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            @error('layer_base')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="layer_shadow" class="block text-gray-700 text-sm font-bold mb-2">Shadow Image</label>
                            <input type="file" name="layer_shadow" id="layer_shadow" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            @error('layer_shadow')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="active" class="form-checkbox h-5 w-5 text-gray-600" {{ old('active') ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Active</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-2">Ornaments</h3>
                            <div id="ornaments-container">
                                @foreach($ornaments as $ornament)
                                    <div class="mb-4 p-4 border rounded">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="ornaments[{{ $ornament->id }}][id]" value="{{ $ornament->id }}" class="form-checkbox h-5 w-5 text-blue-600">
                                            <span class="ml-2">{{ $ornament->name }}</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-gray-700 text-sm font-bold mb-2">Position X</label>
                                                <input type="number" name="ornaments[{{ $ornament->id }}][position_x]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 text-sm font-bold mb-2">Position Y</label>
                                                <input type="number" name="ornaments[{{ $ornament->id }}][position_y]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.jerseys.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin> 