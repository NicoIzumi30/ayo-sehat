<?php

namespace App\Services;

class GeminiService
{
  protected array $apiKeys;
  protected string $model;
  protected string $baseUrlTemplate;

  public function __construct()
  {
    $this->apiKeys = config('services.gemini.api_keys', []);
    $this->model = config('services.gemini.model', 'gemini-2.0-flash-lite');
    $this->baseUrlTemplate = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key=";
  }

  /**
   * Analisis makanan dari foto — identifikasi nama, bahan, metode masak
   */
  public function analyzeFoodPhoto(string $imagePath): ?array
  {
    $imageData = base64_encode(file_get_contents($imagePath));
    $mimeType = mime_content_type($imagePath);

    $prompt = <<<PROMPT
Analisis foto makanan ini dengan sangat detail. Berikan data dalam format JSON (tanpa markdown code block) dengan struktur berikut:
{
  "nama_makanan": "Nama makanan yang teridentifikasi",
  "metode_masak": "Cara memasak (goreng/rebus/kukus/panggang/mentah/tumis/dll)",
  "bahan_bahan": [
    {"nama": "nama bahan", "jumlah": "estimasi jumlah", "satuan": "gram/ml/buah/sdm/dll"}
  ]
}
Perkirakan bahan-bahan utama yang terlihat beserta estimasi jumlahnya. Gunakan Bahasa Indonesia.
PROMPT;

    $data = [
      'contents' => [
        [
          'parts' => [
            ['text' => $prompt],
            ['inline_data' => ['mime_type' => $mimeType, 'data' => $imageData]],
          ],
        ]
      ],
    ];

    return $this->sendRequest($data);
  }

  /**
   * Analisis kalori berdasarkan data makanan (nama, bahan, metode masak)
   */
  public function analyzeCalories(string $namaMakanan, string $metodeMasak, array $bahanBahan): ?array
  {
    $bahanText = collect($bahanBahan)->map(function ($b) {
      return "- {$b['nama']}: {$b['jumlah']} {$b['satuan']}";
    })->implode("\n");

    $prompt = <<<PROMPT
Analisis nilai gizi dan kalori dari makanan berikut:

Nama Makanan: {$namaMakanan}
Metode Masak: {$metodeMasak}
Bahan-bahan:
{$bahanText}

Berikan hasil dalam format JSON (tanpa markdown code block) dengan struktur:
{
  "total_kalori": angka (kcal),
  "protein": angka (gram),
  "karbohidrat": angka (gram),
  "lemak": angka (gram),
  "serat": angka (gram),
  "penjelasan": "Penjelasan detail mengapa kalorinya segitu, termasuk kontribusi tiap bahan dan pengaruh metode masak terhadap kalori. Gunakan Bahasa Indonesia."
}

Berikan estimasi yang akurat berdasarkan porsi dan bahan yang disebutkan.
PROMPT;

    $data = [
      'contents' => [
        [
          'parts' => [['text' => $prompt]],
        ]
      ],
    ];

    return $this->sendRequest($data);
  }

  /**
   * Kirim request ke Gemini API dengan auto key rotation
   * Jika key pertama kena limit (429), otomatis ganti ke key berikutnya
   */
  protected function sendRequest(array $data): ?array
  {
    if (empty($this->apiKeys)) {
      \Log::error('Gemini: Tidak ada API key yang dikonfigurasi');
      return ['error' => 'API key belum dikonfigurasi. Hubungi admin.'];
    }

    foreach ($this->apiKeys as $keyIndex => $apiKey) {
      $url = $this->baseUrlTemplate . $apiKey;
      $keyLabel = 'Key-' . ($keyIndex + 1);

      \Log::info("Gemini: Mencoba {$keyLabel}...");

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

      $response = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $curlError = curl_error($ch);
      curl_close($ch);

      // cURL connection error
      if ($curlError) {
        \Log::error("Gemini {$keyLabel}: cURL error", ['error' => $curlError]);
        return ['error' => 'Koneksi ke server AI gagal: ' . $curlError];
      }

      // Rate limited (429) — coba key berikutnya
      if ($httpCode === 429) {
        \Log::warning("Gemini {$keyLabel}: Rate limited (429), rotasi ke key berikutnya...");
        continue; // langsung coba key berikutnya
      }

      // Error lainnya (bukan 429)
      if ($httpCode !== 200 || !$response) {
        $errorBody = json_decode($response, true);
        $errorMsg = $errorBody['error']['message'] ?? "HTTP {$httpCode}";
        \Log::error("Gemini {$keyLabel}: API error", ['http_code' => $httpCode, 'message' => $errorMsg]);
        return ['error' => 'Gagal menghubungi AI: ' . $errorMsg];
      }

      // Sukses! Parse response
      \Log::info("Gemini {$keyLabel}: Berhasil!");

      $result = json_decode($response, true);
      $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

      if (!$text) {
        \Log::warning("Gemini {$keyLabel}: Response kosong", ['result' => $result]);
        return ['error' => 'AI tidak memberikan respons. Coba lagi.'];
      }

      // Bersihkan markdown code block jika ada
      $text = preg_replace('/^```json\s*/s', '', $text);
      $text = preg_replace('/\s*```$/s', '', $text);
      $text = trim($text);

      $parsed = json_decode($text, true);

      if (json_last_error() !== JSON_ERROR_NONE) {
        \Log::warning("Gemini {$keyLabel}: Response bukan JSON valid", ['text' => $text]);
        return ['error' => 'Format respons AI tidak valid. Coba lagi.'];
      }

      return $parsed;
    }

    // Semua key habis kena limit
    $totalKeys = count($this->apiKeys);
    \Log::error("Gemini: Semua {$totalKeys} API key kena rate limit!");
    return ['error' => "Semua {$totalKeys} API key sudah mencapai batas. Coba lagi dalam beberapa menit."];
  }
}
