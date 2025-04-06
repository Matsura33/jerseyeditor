<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create User Jersey') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('user-jerseys.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">User</label>
                            <select name="user_id" id="user_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('user_id') border-red-500 @enderror">
                                <option value="">Select a user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="jersey_id" class="block text-gray-700 text-sm font-bold mb-2">Base Jersey</label>
                            <select name="jersey_id" id="jersey_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('jersey_id') border-red-500 @enderror">
                                <option value="">Select a jersey</option>
                                @foreach($jerseys as $jersey)
                                    <option value="{{ $jersey->id }}" {{ old('jersey_id') == $jersey->id ? 'selected' : '' }}>{{ $jersey->name }}</option>
                                @endforeach
                            </select>
                            @error('jersey_id')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="texture_url" class="block text-gray-700 text-sm font-bold mb-2">Texture Image</label>
                            <input type="file" name="texture_url" id="texture_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('texture_url') border-red-500 @enderror">
                            @error('texture_url')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="resize_value" class="block text-gray-700 text-sm font-bold mb-2">Resize Value</label>
                            <input type="number" name="resize_value" id="resize_value" step="0.1" min="0.1" max="2" value="{{ old('resize_value', 1) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('resize_value') border-red-500 @enderror">
                            @error('resize_value')
                                <p class="text-red-500 text-xs italic">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="active" class="form-checkbox h-5 w-5 text-blue-600" {{ old('active', true) ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Active</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-lg font-semibold mb-2">Ornaments</h3>
                            <div id="ornaments-container">
                                @foreach($ornaments as $ornament)
                                    <div class="mb-4 p-4 border rounded">
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="ornaments[{{ $ornament->id }}][id]" value="{{ $ornament->id }}" 
                                                class="form-checkbox h-5 w-5 text-blue-600">
                                            <span class="ml-2">{{ $ornament->name }}</span>
                                        </div>
                                        <div class="ml-7">
                                            <label class="block text-gray-700 text-sm font-bold mb-2">Version</label>
                                            <select name="ornaments[{{ $ornament->id }}][version_id]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                <option value="">Select a version</option>
                                                @foreach($ornament->versions as $version)
                                                    <option value="{{ $version->id }}">{{ $version->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="ml-7 grid grid-cols-2 gap-4 mt-2">
                                            <div>
                                                <label class="block text-gray-700 text-sm font-bold mb-2">Position X</label>
                                                <input type="number" name="ornaments[{{ $ornament->id }}][position_x]" value="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            </div>
                                            <div>
                                                <label class="block text-gray-700 text-sm font-bold mb-2">Position Y</label>
                                                <input type="number" name="ornaments[{{ $ornament->id }}][position_y]" value="0" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('user-jerseys.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Create User Jersey
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 