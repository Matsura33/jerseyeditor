<x-layouts.app>
    <div class="min-h-screen bg-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Create Your Jersey</h1>
                <p class="text-xl text-gray-600">Choose a jersey model to start customizing</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($jerseys as $jersey)
                    <a href="{{ route('editor.edit', $jersey) }}" class="group">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-300 transform group-hover:scale-105">
                            <div class="relative">
                                <img src="{{ Storage::url($jersey->layer_base) }}" alt="{{ $jersey->name }}" class="w-full h-64 object-contain">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $jersey->name }}</h3>
                                <p class="text-gray-600">{{ $jersey->description }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app> 