<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ProductImageAiService
{
    public function enhance(UploadedFile $image, array $productData, ?string $customPrompt = null): string
    {
        $apiKey = env('STABILITY_API_KEY');

        if (!$apiKey) {
            throw new RuntimeException('STABILITY_API_KEY belum diatur di file .env. Dapatkan key di platform.stability.ai');
        }

        $response = Http::withToken($apiKey)
            ->timeout(120)
            ->attach(
                'image',
                fopen($image->getRealPath(), 'r'),
                $image->getClientOriginalName()
            )
            ->withHeaders(['Accept' => 'application/json'])
            ->post('https://api.stability.ai/v2beta/stable-image/upscale/fast', [
                // Fast upscaler doesn't need extra parameters, just image
            ]);

        if ($response->failed()) {
            $message = data_get($response->json(), 'message', 'Gagal melakukan upscaling foto produk (HD).');
            throw new RuntimeException($message);
        }

        $base64Image = data_get($response->json(), 'image');

        if (!$base64Image) {
            throw new RuntimeException('Respons AI tidak berisi gambar HD. Pastikan resolusi asli tidak terlalu besar.');
        }

        $imageBytes = base64_decode($base64Image, true);

        if ($imageBytes === false) {
            throw new RuntimeException('Gambar AI HD tidak bisa diproses.');
        }

        return $imageBytes;
    }
}
