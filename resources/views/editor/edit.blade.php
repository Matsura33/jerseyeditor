<x-layouts.app>
    <div class="min-h-screen bg-gray-100">
        <form id="jersey-form" action="{{ route('editor.save') }}" method="POST" class="flex h-screen">
            @csrf
            <input type="hidden" name="jersey_id" value="{{ $jersey->id }}">
            <input type="hidden" name="texture_url" id="texture-url">
            <input type="hidden" name="texture_size" id="texture-size">
            <input type="hidden" name="ornaments_data" id="ornaments-data">

            <!-- Main Content - Jersey Preview -->
            <div class="flex-1 flex flex-col">
                <!-- Jersey Preview Area -->
                <div class="flex-1 flex items-center justify-center bg-gray-50 p-8">
                    <div class="relative w-full max-w-2xl aspect-square">
                        <!-- Base Layer -->
                        <img src="{{ Storage::url($jersey->layer_base) }}" 
                             alt="Base Jersey" 
                             class="absolute inset-0 w-full h-full object-contain z-10">
                        
                        <!-- Texture Layer -->
                        <div id="texture-layer" class="absolute inset-0 w-full h-full opacity-50 z-20">
                            <!-- Texture will be added here via JavaScript -->
                        </div>
                        
                        <!-- Ornaments Layer -->
                        <div id="ornaments-layer" class="absolute inset-0 w-full h-full z-40">
                            <!-- Ornaments will be added here via JavaScript -->
                        </div>

                        <!-- Shadow Layer -->
                        <img src="{{ Storage::url($jersey->layer_shadow) }}" 
                             alt="Shadow" 
                             class="absolute inset-0 w-full h-full object-contain z-30">
                    </div>
                </div>
            </div>

            <!-- Right Sidebar - Controls -->
            <div class="w-80 bg-white border-l border-gray-200 p-4 overflow-y-auto">
                <h2 class="text-lg font-semibold mb-4">Customization</h2>
                
                <!-- Texture Section -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Texture</h3>
                    
                    <!-- Prompt Area -->
                    <div class="mb-4">
                        <textarea name="prompt"
                            class="w-full h-24 p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Describe your jersey design..."></textarea>
                        <button type="button" 
                                id="generate-textures"
                                class="w-full mt-2 bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Générer
                        </button>
                    </div>

                    <div class="grid grid-cols-3 gap-2 mb-4">
                        @foreach(['texture1.jpg', 'texture2.webp', 'texture3.jpg'] as $texture)
                            <button type="button" class="texture-option p-2 border rounded hover:border-indigo-500" data-texture-url="{{ asset('textures/' . $texture) }}">
                                <img src="{{ asset('textures/' . $texture) }}" alt="Texture" class="w-full h-16 object-cover">
                            </button>
                        @endforeach
                    </div>
                    
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Texture Size</label>
                            <input type="range" 
                                   id="texture-size-slider"
                                   min="0" 
                                   max="200" 
                                   value="100" 
                                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <div class="text-sm text-gray-500 text-center" id="texture-size-value">100%</div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Texture Hue</label>
                            <input type="range" 
                                   id="texture-hue-slider"
                                   min="0" 
                                   max="360" 
                                   value="0" 
                                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <div class="text-sm text-gray-500 text-center" id="texture-hue-value">0°</div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Texture Position</label>
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" class="texture-move p-2 border rounded hover:border-indigo-500 flex items-center justify-center" data-direction="up">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </button>
                                <button type="button" class="texture-move p-2 border rounded hover:border-indigo-500 flex items-center justify-center" data-direction="left">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                <button type="button" class="texture-move p-2 border rounded hover:border-indigo-500 flex items-center justify-center" data-direction="right">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                                <div class="col-span-3">
                                    <button type="button" class="texture-move p-2 border rounded hover:border-indigo-500 flex items-center justify-center w-full" data-direction="down">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ornaments Section -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Ornaments</h3>
                    <div class="space-y-4">
                        @foreach($jersey->ornaments as $ornament)
                            <div class="border rounded-lg p-3" data-ornament-id="{{ $ornament->id }}">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium">{{ $ornament->name }}</span>
                                    <div class="flex space-x-2">
                                        <button type="button" class="version-prev p-1 rounded hover:bg-gray-100" data-ornament-id="{{ $ornament->id }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        <button type="button" class="version-next p-1 rounded hover:bg-gray-100" data-ornament-id="{{ $ornament->id }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="version-preview">
                                    @php
                                        $versions = $ornament->versions()->where('active', true)->get();
                                        $firstVersion = $versions->first();
                                    @endphp
                                    <img src="{{ $firstVersion ? Storage::url($firstVersion->image_url) : '' }}" 
                                         alt="{{ $ornament->name }}" 
                                         class="w-full h-16 object-contain"
                                         data-current-version="{{ $firstVersion ? $firstVersion->id : '' }}"
                                         data-versions="{{ $versions->map(function($version) {
                                             return [
                                                 'id' => $version->id,
                                                 'image_url' => Storage::url($version->image_url)
                                             ];
                                         })->toJson() }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Save Button -->
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Save Jersey
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    @vite(['resources/js/editor.js'])
    @endpush
</x-layouts.app> 