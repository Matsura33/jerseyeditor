<?php

namespace App\Http\Controllers;

use App\Models\Jersey;
use App\Models\UserJersey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class EditorController extends Controller
{
    public function index()
    {
        $jerseys = Jersey::where('active', true)->get();
        return view('editor.index', compact('jerseys'));
    }

    public function edit(Jersey $jersey)
    {
        $jersey->load(['ornaments.versions' => function($query) {
            $query->where('active', true);
        }]);
        return view('editor.edit', compact('jersey'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jersey_id' => 'required|exists:jerseys,id',
            'texture_url' => 'required|url',
            'texture_size' => 'required|integer|min:0|max:200',
            'prompt' => 'nullable|string',
            'ornaments_data' => 'required|array'
        ]);

        $userJersey = Auth::user()->userJerseys()->create($validated);

        return redirect()->route('user.jerseys.index')
            ->with('success', 'Jersey created successfully.');
    }

    public function sendPrompt(Request $request)
    {
        try {
            $request->validate([
                'prompt' => 'required|string|min:3'
            ]);

            $apiKey = env('OPENAI_API_KEY');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a prompt optimization assistant specifically for the flux-schnell image model. Your task is to rewrite the user\'s prompt to create high-quality esports jersey textures. Focus on creating detailed, professional-looking patterns and designs suitable for esports apparel. If the initial prompt contains the word jersey, you will have to do your prompt without mentioning jersey. The prompt need to be oriented to get a square texture without the shape of a jersey.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $request->prompt
                    ]
                ],
                'max_tokens' => 800,
                'temperature' => 0.7,
            ]);
            
            $result = $response->json();
            $statusCode = $response->status();
            session()->flash('openai_response', 'OpenAI Response Code: ' . $statusCode);
            
            if (isset($result['choices'][0]['message']['content'])) {
                $returnPrompt = $result['choices'][0]['message']['content'];
                $textures = $this->generateImagesWithGetImg($returnPrompt);
            } else {
                session()->flash('openai_error', 'No content in OpenAI response. Status: ' . $statusCode);
            }

            return response()->json([
                'success' => true,
                'textures' => $textures
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function generateImagesWithGetImg($prompt)
    {
        // Use getimg.ai API to generate images
        $apiKey = env('GETIMG_API_KEY');
        
        // Debug - let's add this to our session with more details
        $hasKey = !empty($apiKey);
        session()->flash('api_debug', 'API Key found: ' . ($hasKey ? 'Yes (length: ' . strlen($apiKey) . ')' : 'No'));
        
        if (empty($apiKey)) {
            // Return some placeholder images for development
            $placeholders = [
                'https://via.placeholder.com/800x800/FF5733/FFFFFF?text=Texture+1',
                'https://via.placeholder.com/800x800/33FF57/FFFFFF?text=Texture+2',
                'https://via.placeholder.com/800x800/3357FF/FFFFFF?text=Texture+3',
            ];
            session()->flash('placeholder_info', 'Using placeholders because API key is empty');
            return $placeholders;
        }
        
        $generatedImages = [];
        
        // Create an array of slightly different prompts for variety
        $promptVariations = [
            'The image has to be a square texture following this prompt: ' . $prompt . ' only square texture, elegant, realistic, textile pattern, wearable',
            'The image has to be a square texture following this prompt: ' . $prompt . ' only square texture, modern, dynamic, textile pattern, wearable',
            'The image has to be a square texture following this prompt: ' . $prompt . ' only square texture, technical, refined, textile pattern, wearable',
        ];
        
        session()->flash('textures_debug', 'Attempting to generate 3 separate images');
        
        // Call API 3 times to generate 3 different images
        for ($i = 0; $i < 3; $i++) {
            try {
                // Log request details
                session()->flash('getimg_request_' . $i, 'Request #' . ($i+1) . ' to getimg.ai with prompt: ' . substr($promptVariations[$i], 0, 100) . '...');
                
                $requestData = [
                    'model' => 'flux-schnell',
                    'prompt' => $promptVariations[$i],
                    'negative_prompt' => 'jersey image, clothes, poor quality, blurry, distorted, text, watermark, signature, logo',
                    'width' => 1024,
                    'height' => 1024,
                    'num_images' => 1,
                    'guidance' => 7.5,
                    'steps' => 6,
                    'output_format' => "png",
                    'response_format' => "url",
                ];
                
                // API URL (primary)
                $apiUrl = 'https://api.getimg.ai/v1/flux-schnell/text-to-image';
                
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post($apiUrl, $requestData);
                
                // Get status and response details
                $statusCode = $response->status();
                $isSuccessful = $response->successful();
                $result = $response->json();
                
                // Log detailed response information
                session()->flash('api_response_' . $i, 'API Response #' . ($i+1) . ' from ' . $apiUrl . ': Status ' . $statusCode . ', Success: ' . ($isSuccessful ? 'Yes' : 'No'));
                
                // Check if image exists in the response
                if (isset($result['image']) && !empty($result['image'])) {
                    session()->flash('image_' . $i . '_type', 'Found image in response #' . ($i+1));
                    $generatedImages[] = $result['image'];
                } else {
                    // No image in the response, try alternative endpoint
                    session()->flash('api_error_' . $i, 'No image found in response #' . ($i+1) . ': ' . json_encode(array_keys($result)));
                    
                    // Try alternative URL
                    $apiUrlAlt = 'https://api.getimg.ai/v1/flux-schnell/text-to-image';
                    
                    session()->flash('getimg_request_alt_' . $i, 'Trying alternative API URL for request #' . ($i+1));
                    
                    $responseAlt = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type' => 'application/json',
                    ])->post($apiUrlAlt, [
                        'model' => 'flux-schnell',
                        'prompt' => $promptVariations[$i],
                        'negative_prompt' => 'poor quality, blurry, distorted, text, watermark, signature, logo',
                        'width' => 1024,
                        'height' => 1024,
                        'num_images' => 1,
                        'output_format' => "png",
                        'response_format' => "url",
                    ]);
                    
                    $statusCodeAlt = $responseAlt->status();
                    $isSuccessfulAlt = $responseAlt->successful();
                    $resultAlt = $responseAlt->json();
                    
                    session()->flash('api_response_alt_' . $i, 'Alternative API Response #' . ($i+1) . ': Status ' . $statusCodeAlt . ', Success: ' . ($isSuccessfulAlt ? 'Yes' : 'No'));

                    if (isset($resultAlt['url']) && !empty($resultAlt['url'])) {
                        session()->flash('image_' . $i . '_type', 'Found image in alternative response #' . ($i+1));
                        $generatedImages[] = $resultAlt['url'];
                    } else {
                        // Add a placeholder for this failed request
                        session()->flash('api_error_alt_' . $i, 'No image found in alternative response #' . ($i+1));
                        $generatedImages[] = 'https://via.placeholder.com/800x800/' . dechex(mt_rand(0, 16777215)) . '/FFFFFF?text=API+Error+';
                    }
                }
            } catch (\Exception $e) {
                // Log detailed error information
                session()->flash('api_error_' . $i, 'Exception in request #' . ($i+1) . ': ' . $e->getMessage());
                // Add a placeholder for this failed request
                $generatedImages[] = $e->getMessage();
            }
        }
        
        // Check if we have 3 images
        if (count($generatedImages) === 3) {
            session()->flash('images_found', 'Successfully generated all 3 images');
        } else {
            // Fill in any missing images with placeholders
            session()->flash('images_found', 'Generated ' . count($generatedImages) . ' out of 3 images');
            while (count($generatedImages) < 3) {
                $index = count($generatedImages) + 1;
                $generatedImages[] = 'https://via.placeholder.com/800x800/' . dechex(mt_rand(0, 16777215)) . '/FFFFFF?text=Missing+' . $index;
            }
        }
        
        return $generatedImages;
    }
} 