<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductFaq;
use App\Models\ProductPerformance;
use App\Models\ProductSpecGroup;
use App\Models\ProductSpecItem;
use App\Models\ProductSpecValue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ScrapingService
{
    protected $groqApiKey;
    protected $deepseekApiKey;

    public function __construct()
    {
        $this->groqApiKey = config('scraping.groq_api_key');
        $this->deepseekApiKey = config('scraping.deepseek_api_key');
    }

    public function scrapeAndSave(string $url, int $categoryId, int $brandId, ?string $rawContent = null, ?string $titleOverride = null)
    {
        // 1. Fetch HTML (or use provided content)
        if ($rawContent) {
            $content = $rawContent;
        } else {
            $content = $this->fetchHtml($url);
            if (!$content) {
                return ['status' => 'error', 'message' => "Failed to fetch URL: $url"];
            }
        }

        // 2. Prepare Content (Clean HTML/Text) - Bypassing Groq
        $preparedText = $this->prepareContent($content);
        if (!$preparedText) {
            return ['status' => 'error', 'message' => "Failed to prepare content: $url"];
        }

        // 3. Structure with Deepseek
        $structuredData = $this->structureWithDeepseek($preparedText);
        if (!$structuredData) {
            return ['status' => 'error', 'message' => "Failed to structure with Deepseek: $url"];
        }

        // Apply Title Override if provided
        if ($titleOverride) {
            $structuredData['title'] = $titleOverride;
        }

        // 4. Save to Database
        return $this->saveProduct($structuredData, $categoryId, $brandId, $url);
    }

    protected function fetchHtml($url)
    {
        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Connection' => 'keep-alive',
            'Upgrade-Insecure-Requests' => '1',
            'Sec-Fetch-Dest' => 'document',
            'Sec-Fetch-Mode' => 'navigate',
            'Sec-Fetch-Site' => 'cross-site',
            'Sec-Fetch-User' => '?1',
            'Cache-Control' => 'max-age=0',
            'Referer' => 'https://www.google.com/',
        ];

        try {
            // 1. Try Direct Request
            $response = Http::withHeaders($headers)->get($url);
            
            if ($response->successful()) {
                return $response->body();
            }

            // 2. If 429 or 403, Try Google Cache
            if (in_array($response->status(), [403, 429])) {
                \Log::info("Direct fetch failed for $url (Status: {$response->status()}). Trying Google Cache...");
                
                // Wait a bit before fallback
                sleep(2);
                
                $cacheUrl = 'http://webcache.googleusercontent.com/search?q=cache:' . urlencode($url) . '&strip=0&vwsrc=0';
                $cacheResponse = Http::withHeaders($headers)->get($cacheUrl);

                if ($cacheResponse->successful()) {
                    return $cacheResponse->body();
                }
            }

            return null;
        } catch (\Exception $e) {
            \Log::error('Fetch HTML Exception: ' . $e->getMessage());
            return null;
        }
    }

    protected function prepareContent($content)
    {
        // Check if content is HTML
        $isHtml = strip_tags($content) !== $content;

        if (!$isHtml) {
            return substr($content, 0, 30000);
        }

        // --- SPECIFIC EXTRACTION ---
        $extractedInfo = "";

        // 1. Extract Model Name (specs-phone-name-title)
        if (preg_match('/<h1[^>]*class=["\'][^"\']*specs-phone-name-title[^"\']*["\'][^>]*>(.*?)<\/h1>/i', $content, $matches)) {
            $extractedInfo .= "Target Model Name: " . trim(strip_tags($matches[1])) . "\n";
        }

        // 2. Extract Battery Info (help-battery)
        if (preg_match('/<li[^>]*class=["\'][^"\']*help-battery[^"\']*["\'][^>]*>(.*?)<\/li>/is', $content, $matches)) {
            $batteryText = trim(strip_tags($matches[1]));
            $batteryText = preg_replace('/\s+/', ' ', $batteryText);
            $extractedInfo .= "Battery Highlight Info: " . $batteryText . "\n";
        }

        // 3. Isolate main specs list (id="specs-list") to reduce noise
        $workingContent = $content;
        $specsStartPos = strpos($content, 'id="specs-list"');
        
        if ($specsStartPos !== false) {
             // Found the specs list start. 
             // We'll look for the start of the next major section to cut off (comments, sidebar, footer)
             $cutOffMarkers = ['id="user-comments"', 'class="article-info-line page-specs', '<aside', 'id="footer"'];
             $specsEndPos = false;
             
             foreach ($cutOffMarkers as $marker) {
                 $pos = strpos($content, $marker, $specsStartPos);
                 if ($pos !== false) {
                     if ($specsEndPos === false || $pos < $specsEndPos) {
                         $specsEndPos = $pos;
                     }
                 }
             }

             if ($specsEndPos !== false) {
                 $workingContent = substr($content, $specsStartPos, $specsEndPos - $specsStartPos);
             } else {
                 $workingContent = substr($content, $specsStartPos);
             }
        }
        
        // --- STANDARD PREPARATION ---
        // Improve HTML to Text conversion to preserve table structure
        // 1. Replace table row endings and breaks with newlines
        $text = preg_replace('/<\/(tr|div|p|h\d)>/i', "\n", $workingContent);
        $text = preg_replace('/<br\s*\/?>/i', "\n", $text);
        
        // 2. Replace cell endings with a separator to distinguish key/value
        $text = preg_replace('/<\/(td|th)>/i', " ||| ", $text);
        
        // 3. Strip tags
        $text = strip_tags($text);
        
        // 4. Clean up excessive whitespace
        $text = preg_replace('/\n\s*\n/', "\n", $text);
        $text = preg_replace('/[ \t]+/', ' ', $text);
        
        // Prepend extracted info
        $finalText = $extractedInfo . "\n--- SPECS CONTENT ---\n" . $text;

        // Limit text length to avoid hitting token limits (approx 4 chars per token)
        // Increased to 30000 to ensure we capture all specs even on long pages
        return substr($finalText, 0, 30000);
    }

    protected function structureWithDeepseek($text)
    {
        // Prompt for Deepseek
        $prompt = 'Extract the mobile phone specifications from the following GSMArena HTML tables and return a CLEAN JSON output.

=== OUTPUT FORMAT (STRICT) ===
{
  "title": "",
  "meta_title": "",
  "meta_description": "",
  "short_description": "",
  "base_price": "",
  "specs": {
    "Network": { ... },
    "Launch": { ... },
    "Body": { ... },
    "Display": { ... },
    "Hardware & Software": { ... },
    "Memory & Storage": { ... },
    "Main Camera": { ... },
    "Selfie Camera": { ... },
    "Sound": { ... },
    "Connectivity": { ... },
    "Features": { ... },
    "Battery": { ... }
  },
  "faqs": [
      { "question": "...", "answer": "..." },
      ...
  ],
  "performance": {
      "gaming_fps": "60 FPS",
      "battery_sot": "8.5 Hours",
      "camera_score": "8.5/10",
      "antutu": { "score": 0, "version": "" }
  }
}

=== RULES (FOLLOW STRICTLY) ===
1. Read the raw GSMArena HTML tables and extract ALL specification labels exactly as they appear.
   - Do NOT rename keys.
   - Do NOT shorten values (EXCEPT for Network bands, see below).
   - Preserve symbols, units, line breaks, slashes, everything.
   - Ignore "Compare" or "Related" sections to avoid model confusion.

2. Group the extracted specs into the following EXACT groups:
   - Network
   - Launch
   - Body
   - Display
   - Hardware & Software
   - Memory & Storage
   - Main Camera
   - Selfie Camera
   - Sound
   - Connectivity
   - Features
   - Battery
   - Tests

3. Group-mapping rules:
   - Any table titled “Platform” → goes to **Hardware & Software**.
   - Any table titled “Memory” → goes to **Memory & Storage**.
   - Any table titled “Comms” → goes to **Connectivity**.
   - Any table titled “Main Camera” → goes to **Main Camera**.
   - Any table titled “Selfie camera” → goes to **Selfie Camera**.
   - Any table titled “Sound” → Sound.
   - Any table titled “Battery” → Battery.
   - Any table titled “Tests” → Tests.

4. HANDLE SPECIAL ROWS:
   - If a key appears empty (ttl = “&nbsp;”) but value continues next line, MERGE it with previous value.
   - If a row contains text but NO value (e.g., "IP68 dust/water resistant", "24-bit/192kHz audio"), treat that text as the **VALUE**.
   - Assign a descriptive **KEY** based on context (e.g., "Protection", "Audio Quality", "Satellite").

5. NETWORK BANDS (4G/5G):
   - For "4G bands" and "5G bands", provide a SHORT summary.
   - List ONLY the band numbers/names (e.g., "1, 2, 3, 5, 7, 8, 20, 28, 38, 40, 41").
   - Do NOT list every model variant details (e.g., remove " - A2882, A2884...").
   - Keep it concise.

6. BATTERY:
   - Extract Type (Capacity) and Charging information.
   - If exact keys are missing, summarize all battery info under "Type".

7. DESCRIPTIONS & META:
   - FIRST, identify the exact Model Name from the text (e.g., "Xiaomi 15 Ultra").
   - "short_description": Write a detailed summary of [Model Name] features BASED STRICTLY ON THE EXTRACTED SPECS. Do NOT mention previous models (e.g., 14 Ultra if this is 15 Ultra). Approx 800-1000 characters.
   - "meta_title": A concise SEO title (e.g., "[Model Name] Specs, Price in Bangladesh").
   - "meta_description": A compelling SEO description for [Model Name] summarizing key specs. Approx 160 chars.

8. PERFORMANCE (CRITICAL: ESTIMATE BASED ON HARDWARE):
   - You MUST estimate these metrics based on the specific phone specs (Chipset, RAM, Battery, Camera).
   - Gaming: Estimate a realistic FPS value (e.g., "40 FPS", "55 FPS", "90 FPS") based on the Processor/GPU.
   - Battery: Estimate Screen-On-Time (e.g., "6 Hours", "9.5 Hours") based on Battery mAh. Do NOT use "8.5 Hours" unless calculated.
   - Camera Score: Estimate 1-10 (e.g., "7.2/10") based on specs. Do NOT use "8.5/10" unless calculated.
   - AnTuTu: Extract from "Tests" section (often at bottom). Prefer latest version (v10 > v9). Return score (int) and version (string).
   - DO NOT simply return the examples. Calculate unique values for this phone.

9. If price exists, extract lowest numerical price as `base_price`.
10. Generate 5–7 FAQs for [Model Name] based STRICTLY on the extracted specifications.

Now parse the following HTML:
' . substr($text, 0, 30000);

        try {
            $makeRequest = function() use ($prompt) {
                return Http::withToken($this->deepseekApiKey)
                    ->timeout(120) // Increased timeout to 120 seconds
                    ->post('https://api.deepseek.com/chat/completions', [
                        'model' => 'deepseek-chat',
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are a data extraction assistant. Return only valid JSON. Do not include markdown formatting.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'response_format' => ['type' => 'json_object'],
                    ]);
            };

            $response = $makeRequest();

            // Retry on 5xx errors
            if ($response->serverError()) {
                sleep(2);
                $response = $makeRequest();
            }

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                
                // Clean up markdown code blocks if present
                $content = str_replace('```json', '', $content);
                $content = str_replace('```', '', $content);
                $content = trim($content);

                $json = json_decode($content, true);
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    \Log::info('Deepseek Structured Data: ', $json); // Log the data to verify
                    return $json;
                }
                
                \Log::error('Deepseek JSON Decode Error: ' . json_last_error_msg() . ' Content: ' . $content);
                return null;
            }
            
            \Log::error('Deepseek API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            \Log::error('Deepseek Exception: ' . $e->getMessage());
            return null;
        }
    }

    protected function saveProduct($data, $categoryId, $brandId, $url)
    {
        // Check if exists
        $existing = Product::where('title', $data['title'])->first();
        if ($existing) {
            return ['status' => 'skipped', 'message' => "Product already exists: {$data['title']}"];
        }

        // Create Product
        $product = Product::create([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']),
            'category_id' => $categoryId,
            'brand_id' => $brandId,
            'short_description' => $data['short_description'] ?? '',
            'storage_type' => 'backblaze', // Force backblaze as per user request
            'base_price' => isset($data['base_price']) && $data['base_price'] !== '' ? (float) preg_replace('/[^0-9.]/', '', $data['base_price']) : null,
            'status' => 'unofficial', // Set status to unofficial as a safe default
            'is_published' => false,
            'raw_html' => null, // Set to null as requested
            'meta_title' => $data['meta_title'] ?? $data['title'],
            'meta_description' => $data['meta_description'] ?? ($data['short_description'] ?? ''),
        ]);

        // Save Specs
        if (isset($data['specs']) && is_array($data['specs'])) {
            foreach ($data['specs'] as $groupName => $items) {
                // Find or create Group (Current context from LLM)
                $currentGroup = ProductSpecGroup::firstOrCreate(
                    ['name' => $groupName],
                    ['slug' => Str::slug($groupName)]
                );

                foreach ($items as $itemName => $value) {
                    // Skip empty values to keep the display clean
                    if (empty($value) && $value !== '0') {
                        continue;
                    }

                    // 1. Try to find existing item IN THIS GROUP by label (Case Insensitive)
                    // We MUST scope to the group because keys like "Type" or "Size" appear in multiple groups (Display, Battery, etc.)
                    $existingItem = ProductSpecItem::where('product_spec_group_id', $currentGroup->id)
                        ->whereRaw('LOWER(TRIM(label)) = ?', [strtolower(trim($itemName))])
                        ->first();

                    if ($existingItem) {
                        $targetGroupId = $existingItem->product_spec_group_id;
                        $targetItemId = $existingItem->id;
                    } else {
                        // 2. If not found in this group, create it
                        $slug = Str::slug($itemName);
                        
                        // Ensure slug is unique globally (or scoped? Slug should probably be unique globally to avoid conflicts)
                        // But if we have multiple "Type" items, they need unique slugs like "type", "type-1", "type-2"
                        $originalSlug = $slug;
                        $count = 1;
                        while (ProductSpecItem::where('slug', $slug)->exists()) {
                            $slug = $originalSlug . '-' . $count++;
                        }
                        
                        $newItem = ProductSpecItem::create([
                            'product_spec_group_id' => $currentGroup->id,
                            'label' => $itemName,
                            'slug' => $slug,
                            'input_type' => 'text',
                        ]);
                        
                        $targetGroupId = $currentGroup->id;
                        $targetItemId = $newItem->id;
                    }

                    // Create Value only if it doesn't exist for this product and item
                    // This prevents duplicate display if the same spec appears in multiple groups in the source
                    $existingValue = ProductSpecValue::where('product_id', $product->id)
                        ->where('product_spec_item_id', $targetItemId)
                        ->first();

                    if (!$existingValue) {
                        ProductSpecValue::create([
                            'product_id' => $product->id,
                            'product_spec_group_id' => $targetGroupId,
                            'product_spec_item_id' => $targetItemId,
                            'value' => $value,
                        ]);
                    }
                }
            }
        }



        // Save FAQs
        if (isset($data['faqs']) && is_array($data['faqs'])) {
            foreach ($data['faqs'] as $index => $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    ProductFaq::create([
                        'product_id' => $product->id,
                        'question' => $faq['question'],
                        'answer' => $faq['answer'],
                        'sort_order' => $index + 1,
                    ]);
                }
            }
        }

        // Save Performance
        if (isset($data['performance']) && is_array($data['performance'])) {
            ProductPerformance::create([
                'product_id' => $product->id,
                'gaming_fps' => $data['performance']['gaming_fps'] ?? null,
                'battery_sot' => $data['performance']['battery_sot'] ?? null,
                'camera_score' => $data['performance']['camera_score'] ?? null,
            ]);

            // Save Antutu Score if available
            if (isset($data['performance']['antutu']) && !empty($data['performance']['antutu']['score'])) {
                 \App\Models\AntutuScore::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'total_score' => (int) $data['performance']['antutu']['score'],
                        'version' => $data['performance']['antutu']['version'] ?? null,
                    ]
                );
            }
        }

        return ['status' => 'success', 'message' => "Created product: {$product->title}"];
    }
}
