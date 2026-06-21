<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ProductImageAiService
{
    public function enhance(UploadedFile $image, array $productData, ?string $customPrompt = null): string
    {
        $apiKey = config('services.openai.api_key');

        if (!$apiKey) {
            throw new RuntimeException('OPENAI_API_KEY belum diatur di file .env.');
        }

        $prompt = $this->buildPrompt($productData, $customPrompt);

        $response = Http::withToken($apiKey)
            ->timeout((int) config('services.openai.timeout', 120))
            ->attach(
                'image[]',
                fopen($image->getRealPath(), 'r'),
                $image->getClientOriginalName(),
                ['Content-Type' => $image->getMimeType()]
            )
            ->post('https://api.openai.com/v1/images/edits', [
                'model' => config('services.openai.image_model', 'gpt-image-1.5'),
                'prompt' => $prompt,
                'size' => config('services.openai.image_size', '1024x1024'),
                'quality' => config('services.openai.image_quality', 'medium'),
                'output_format' => 'png',
            ]);

        if ($response->failed()) {
            $message = data_get($response->json(), 'error.message', 'Gagal membuat foto produk dengan AI.');
            throw new RuntimeException($message);
        }

        $base64Image = data_get($response->json(), 'data.0.b64_json');

        if (!$base64Image) {
            throw new RuntimeException('Respons AI tidak berisi gambar.');
        }

        $imageBytes = base64_decode($base64Image, true);

        if ($imageBytes === false) {
            throw new RuntimeException('Gambar AI tidak bisa diproses.');
        }

        return $imageBytes;
    }

    protected function buildPrompt(array $productData, ?string $customPrompt = null): string
    {
        $name = $productData['name'] ?? 'produk bakery';
        $description = $productData['description'] ?? '';

        $basePrompt = <<<PROMPT
Enhance this bakery product photo for an online catalog.
Keep the product recognizable and do not change its core shape, flavor, label, or toppings.
Create a clean, appetizing, photorealistic product photo with soft natural lighting, sharper detail, balanced color, and a premium bakery display style.
Use an uncluttered background suitable for e-commerce.
Product name: {$name}
Product description: {$description}
PROMPT;

        if ($customPrompt) {
            $basePrompt .= "\nAdditional direction: {$customPrompt}";
        }

        return $basePrompt;
    }
}
