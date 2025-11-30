<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductPerformanceService
{
    public function calculateGamingScore(?string $specs): string
    {
        if (empty($specs)) return 'N/A (Missing Specs)';

        $jsonPath = storage_path('app/data/gaming.json');
        if (!file_exists($jsonPath)) {
            return 'Error: gaming.json not found';
        }

        $data = json_decode(file_get_contents($jsonPath), true);
        if (!$data) {
            return 'Error: Invalid JSON data';
        }

        // Fallback/Override data for new hardware not yet in JSON
        $newHardware = [
            'cpu_scores' => [
                'Snapdragon 8 Elite' => 100,
                'Oryon' => 100,
                'Oryon V3' => 105,
            ],
            'gpu_scores' => [
                'Adreno 840' => 105,
                'Adreno 830' => 100,
            ]
        ];

        // Merge new hardware into loaded data
        $data['cpu_scores'] = array_merge($data['cpu_scores'] ?? [], $newHardware['cpu_scores']);
        $data['gpu_scores'] = array_merge($data['gpu_scores'] ?? [], $newHardware['gpu_scores']);

        $processorScore = 0;
        $gpuScore = 0;
        $matchedProcessor = null;
        $matchedGpu = null;
        $matchedRam = null;
        $matchedResolution = null;

        // 1. Find CPU Model
        // First, try to extract the CPU line from the specs text
        $cpuLine = '';
        if (preg_match('/CPU:\s*(.+)$/m', $specs, $matches)) {
            $cpuLine = $matches[1];
        } else {
            $cpuLine = $specs;
        }

        // Check for manual override in text: "(use 85)"
        if (preg_match('/\(use\s*(\d+)\)/i', $cpuLine, $manualMatch)) {
            $processorScore = (int) $manualMatch[1];
            $matchedProcessor = trim(preg_replace('/\(use\s*\d+\)/i', '', $cpuLine)); 
        } else {
            // Exact/Known Match
            foreach ($data['cpu_scores'] as $processor => $score) {
                if (stripos($cpuLine, $processor) !== false) {
                    if ($score > $processorScore) {
                        $processorScore = $score;
                        $matchedProcessor = $processor;
                    }
                }
            }

            // Generic Fallback if no exact match found
            if ($processorScore == 0) {
                if (preg_match('/Snapdragon 8 Gen (\d)/i', $cpuLine, $m)) {
                    $gen = (int)$m[1];
                    $processorScore = 85 + ($gen * 5); // Gen 1=90, Gen 2=95, Gen 3=100...
                    $matchedProcessor = "Snapdragon 8 Gen $gen (Est)";
                } elseif (preg_match('/Dimensity 9(\d)00/i', $cpuLine, $m)) {
                    $processorScore = 90 + ((int)$m[1] * 2); 
                    $matchedProcessor = "Dimensity 9{$m[1]}00 (Est)";
                } elseif (stripos($cpuLine, 'Cortex-X4') !== false) {
                    $processorScore = 98; // Flagship 2024 level
                    $matchedProcessor = "High-End (Cortex-X4)";
                } elseif (stripos($cpuLine, 'Cortex-X3') !== false) {
                    $processorScore = 95; // Flagship 2023 level
                    $matchedProcessor = "High-End (Cortex-X3)";
                }
            }
        }

        // 2. Find GPU Model
        $gpuLine = '';
        if (preg_match('/GPU:\s*(.+)$/m', $specs, $matches)) {
            $gpuLine = $matches[1];
        } else {
            $gpuLine = $specs;
        }

        // Check for manual override in text: "(use 88)"
        if (preg_match('/\(use\s*(\d+)\)/i', $gpuLine, $manualMatch)) {
            $gpuScore = (int) $manualMatch[1];
            $matchedGpu = trim(preg_replace('/\(use\s*\d+\)/i', '', $gpuLine));
        } else {
            // Exact/Known Match
            foreach ($data['gpu_scores'] as $gpu => $score) {
                if (stripos($gpuLine, $gpu) !== false) {
                    if ($score > $gpuScore) {
                        $gpuScore = $score;
                        $matchedGpu = $gpu;
                    }
                }
            }

            // Generic Fallback for Adreno Series
            if ($gpuScore == 0) {
                if (preg_match('/Adreno (\d)(\d{2})/i', $gpuLine, $m)) {
                    $series = (int)$m[1];
                    $model = (int)$m[2];
                    if ($series >= 8) {
                        $gpuScore = 100 + ($model / 10); // 825 -> 100 + 2.5 = 102.5
                        $matchedGpu = "Adreno {$series}{$model} (Est)";
                    } elseif ($series == 7) {
                        $gpuScore = 85 + ($model / 5); // 730=91, 740=93...
                        $matchedGpu = "Adreno {$series}{$model} (Est)";
                    }
                }
            }
        }

        // 3. Find RAM (Amount + Type check)
        // Simple check for GB presence and Type (LPDDR/DDR)
        $ramAmount = 0;
        $ramImpact = 0;
        if (preg_match('/(\d+)\s*GB/i', $specs, $ramMatches)) {
            $matchedRam = $ramMatches[0];
            $ramAmount = (int) $ramMatches[1];
            // RAM Impact Calculation: 8GB = 16, so assuming Impact = Amount * 2
            $ramImpact = $ramAmount * 2;
        }

        // 4. Find Display Resolution
        $resolutionMultiplier = 1.0; // Default placeholder
        
        // Try to parse "Width x Height" format first
        $parsedRes = null;
        if (preg_match('/(\d{3,4})\s*[x×]\s*(\d{3,4})/i', $specs, $resMatches)) {
            $w = (int) $resMatches[1];
            $h = (int) $resMatches[2];
            $pixels = $w * $h;
            
            // 2K/QHD is roughly 3.7M pixels (2560x1440) or higher
            // 1.5K is roughly 3M - 3.6M pixels
            // 1080p/FHD is roughly 2M pixels (1920x1080)
            
            if ($pixels >= 3600000) { // > ~3.6MP -> 2K
                $parsedRes = '2K/1440p';
            } elseif ($pixels >= 2700000) { // > ~2.7MP -> 1.5K
                $parsedRes = '1.5K';
            } elseif ($pixels >= 2000000) { // > ~2MP -> 1080p
                $parsedRes = '1080p';
            } else {
                $parsedRes = '720p';
            }
        }

        if ($parsedRes) {
            $matchedResolution = $parsedRes;
            if ($parsedRes === '2K/1440p') {
                $resolutionMultiplier = $data['resolution_impact']['2k'] ?? 0.9;
            } elseif ($parsedRes === '1.5K') {
                $resolutionMultiplier = 0.95; // Slightly better than 2K
            } elseif ($parsedRes === '1080p') {
                $resolutionMultiplier = $data['resolution_impact']['1080p'] ?? 1.0;
            } else {
                $resolutionMultiplier = $data['resolution_impact']['720p'] ?? 1.1;
            }
        } elseif (stripos($specs, '2k') !== false || stripos($specs, '1440p') !== false) {
            $matchedResolution = '2K/1440p';
            $resolutionMultiplier = $data['resolution_impact']['2k'] ?? 0.9;
        } elseif (stripos($specs, '1.5k') !== false) {
            $matchedResolution = '1.5K';
            $resolutionMultiplier = 0.95;
        } elseif (stripos($specs, '1080p') !== false || stripos($specs, 'FHD') !== false) {
            $matchedResolution = '1080p';
            $resolutionMultiplier = $data['resolution_impact']['1080p'] ?? 1.0;
        } elseif (stripos($specs, '720p') !== false || stripos($specs, 'HD+') !== false) {
            $matchedResolution = '720p';
            $resolutionMultiplier = $data['resolution_impact']['720p'] ?? 1.1;
        }

        // Check if all 4 mandatory fields are present
        if (!$matchedProcessor || !$matchedGpu || !$matchedRam || !$matchedResolution) {
            $missing = [];
            if (!$matchedProcessor) $missing[] = 'CPU';
            if (!$matchedGpu) $missing[] = 'GPU';
            if (!$matchedRam) $missing[] = 'RAM';
            if (!$matchedResolution) $missing[] = 'Resolution';
            
            return "N/A (Missing: " . implode(', ', $missing) . ")";
        }

        // Calculate Hardware Score
        // Formula: (CPU + GPU + RAM Impact) / 3
        $hardwareScore = ($processorScore + $gpuScore + $ramImpact) / 3;

        // Apply Multipliers
        // User request: PUBG Smooth Multiplier = 1.0 (Default)
        $pubgMultiplier = $data['game_multipliers']['pubg_smooth'] ?? 1.0;
        
        $estimatedFps = $hardwareScore * $pubgMultiplier * $resolutionMultiplier;

        return round($estimatedFps) . " FPS";
    }

    public function calculateBatteryScore(?string $specs): string
    {
        if (empty($specs)) return 'N/A (Missing Specs)';

        $jsonPath = storage_path('app/data/battery_performance.json');
        if (!file_exists($jsonPath)) {
            return 'Error: battery_performance.json not found';
        }

        $data = json_decode(file_get_contents($jsonPath), true);
        if (!$data) {
            return 'Error: Invalid JSON data';
        }

        $mah = 0;
        $charging = 0;
        $screenSize = 0.0;
        $resolution = '';
        $cpu = '';

        // Extract mAh
        if (preg_match('/(\d{3,5})\s*mAh/i', $specs, $matches)) {
            $mah = (int) $matches[1];
        }

        // Extract Charging Speed (W)
        if (preg_match('/(\d{1,3})\s*W/i', $specs, $matches)) {
            $charging = (int) $matches[1];
        }

        // Extract Screen Size
        if (preg_match('/(\d+(\.\d+)?)\s*(inch|")/i', $specs, $matches)) {
            $screenSize = (float) $matches[1];
        }

        // Extract Resolution
        if (stripos($specs, '2k') !== false || stripos($specs, '1440p') !== false) {
            $resolution = '2k';
        } elseif (stripos($specs, '1.5k') !== false) {
             $resolution = '1.5k';
        } elseif (stripos($specs, '1080p') !== false || stripos($specs, 'FHD') !== false) {
            $resolution = '1080p';
        } elseif (stripos($specs, '720p') !== false || stripos($specs, 'HD+') !== false) {
            $resolution = '720p';
        } else {
             if (preg_match('/(\d{3,4})\s*[x×]\s*(\d{3,4})/i', $specs, $resMatches)) {
                $w = (int) $resMatches[1];
                $h = (int) $resMatches[2];
                $pixels = $w * $h;
                if ($pixels >= 3600000) $resolution = '2k';
                elseif ($pixels >= 2700000) $resolution = '1.5k';
                elseif ($pixels >= 2000000) $resolution = '1080p';
                else $resolution = '720p';
             }
        }

        // Extract CPU
        if (preg_match('/CPU:\s*(.+)$/m', $specs, $matches)) {
            $cpu = trim($matches[1]);
        }

        // --- Calculation Logic ---

        // 1. Base SOT from mAh (Assume 5000mAh = 100 points)
        $capacityScore = $mah / 50; 

        // 2. CPU Efficiency
        $cpuEfficiency = 80; // Default
        $foundCpu = false;
        if (isset($data['cpu_efficiency'])) {
            foreach ($data['cpu_efficiency'] as $key => $score) {
                if (stripos($cpu, $key) !== false) {
                    $cpuEfficiency = $score;
                    $foundCpu = true;
                    break;
                }
            }
        }
        
        // Fallback for unknown CPUs
        if (!$foundCpu) {
            if (stripos($cpu, 'Oryon') !== false || stripos($cpu, 'Snapdragon 8 Elite') !== false) {
                $cpuEfficiency = 95; // Very efficient new architecture
            } elseif (preg_match('/Snapdragon 8 Gen (\d)/i', $cpu, $m)) {
                $gen = (int)$m[1];
                if ($gen >= 3) $cpuEfficiency = 90;
            } elseif (stripos($cpu, 'Cortex-X4') !== false) {
                $cpuEfficiency = 90;
            }
        }

        // Normalize CPU Efficiency (80 is baseline 1.0)
        $cpuFactor = $cpuEfficiency / 80;

        // 3. Resolution Penalty
        $resPenalty = 1.0;
        $lookupRes = $resolution;
        if ($resolution == '1.5k') $lookupRes = '2k'; 
        
        if (isset($data['resolution_penalty'][$lookupRes])) {
            $resPenalty = $data['resolution_penalty'][$lookupRes];
        }

        // 4. Screen Size Penalty
        $sizePenalty = 1.0;
        if ($screenSize > 0 && isset($data['screen_size_penalty'])) {
            $baseSize = $data['screen_size_penalty']['base_size'];
            $multiplier = $data['screen_size_penalty']['multiplier_per_inch'];
            if ($screenSize > $baseSize) {
                $diff = $screenSize - $baseSize;
                $sizePenalty = 1.0 + ($diff * $multiplier);
            }
        }

        $finalScore = ($capacityScore * $cpuFactor) / ($resPenalty * $sizePenalty);

        // Map Final Score to SOT Hours
        $estimatedSot = ($finalScore / 100) * 8.0; 
        
        $hours = floor($estimatedSot);
        $minutes = round(($estimatedSot - $hours) * 60);

        if ($minutes == 60) {
            $hours++;
            $minutes = 0;
        }

        if ($minutes > 0) {
            return "{$hours} Hours {$minutes} Min";
        }
        return "{$hours} Hours";
    }

    public function calculateCameraScore(?string $specs): string
    {
        if (empty($specs)) return 'N/A (Missing Specs)';

        $mp = 0;
        $sensorSize = 0.0; // 1/X format, so smaller X is bigger sensor. We'll store X.
        $aperture = 0.0;
        $hasOis = false;
        $hasEis = false;

        // 1. Extract MP (Main Camera)
        if (preg_match('/(\d{2,3})\s*MP/i', $specs, $matches)) {
            $mp = (int) $matches[1];
        }

        // 2. Extract Sensor Size (e.g., 1/1.56", 1/1.3")
        // Looking for pattern like 1/1.xx"
        if (preg_match('/1\/(\d+(\.\d+)?)"/i', $specs, $matches)) {
            $sensorSize = (float) $matches[1]; 
        }

        // 3. Extract Aperture (e.g., f/1.8, f/1.6)
        if (preg_match('/f\/(\d+(\.\d+)?)/i', $specs, $matches)) {
            $aperture = (float) $matches[1];
        }

        // 4. Check for OIS / EIS
        if (stripos($specs, 'OIS') !== false || stripos($specs, 'Optical Image Stabilization') !== false) {
            $hasOis = true;
        }
        if (stripos($specs, 'EIS') !== false || stripos($specs, 'Electronic Image Stabilization') !== false) {
            $hasEis = true;
        }

        // --- Calculation Logic ---
        
        // Base Score from MP (Max 40 points)
        // 200MP = 40, 108MP = 38, 50MP = 35, 12MP = 25
        $scoreMp = 0;
        if ($mp >= 200) $scoreMp = 40;
        elseif ($mp >= 100) $scoreMp = 38;
        elseif ($mp >= 50) $scoreMp = 35;
        elseif ($mp >= 48) $scoreMp = 32;
        elseif ($mp >= 12) $scoreMp = 25;
        else $scoreMp = 15;

        // Sensor Size Score (Max 30 points)
        // 1/1.0" (1.0) is best. 1/2.0" is smaller.
        // Formula: 30 / sensor_denominator * 1.0 (approx)
        // 1 inch = 30 pts
        // 1/1.3 = 23 pts
        // 1/1.5 = 20 pts
        // 1/2.0 = 15 pts
        $scoreSensor = 0;
        if ($sensorSize > 0) {
            $scoreSensor = 30 / $sensorSize; 
            if ($scoreSensor > 30) $scoreSensor = 30; // Cap at 1 inch
        } else {
            // Fallback if sensor size not found but MP is high
            if ($mp >= 50) $scoreSensor = 18; // Assume decent sensor
            else $scoreSensor = 12;
        }

        // Aperture Score (Max 15 points)
        // f/1.4 = 15, f/1.8 = 10, f/2.2 = 5
        $scoreAperture = 0;
        if ($aperture > 0) {
            if ($aperture <= 1.4) $scoreAperture = 15;
            elseif ($aperture <= 1.6) $scoreAperture = 13;
            elseif ($aperture <= 1.8) $scoreAperture = 10;
            elseif ($aperture <= 2.0) $scoreAperture = 8;
            else $scoreAperture = 5;
        } else {
            $scoreAperture = 8; // Average fallback
        }

        // OIS/EIS Score (Max 15 points)
        $scoreStabilization = 0;
        if ($hasOis) $scoreStabilization += 15;
        elseif ($hasEis) $scoreStabilization += 5;

        $totalScore = $scoreMp + $scoreSensor + $scoreAperture + $scoreStabilization;

        // Cap at 100
        if ($totalScore > 100) $totalScore = 100;

        return round($totalScore) . "/100";
    }

    public function getSpecsText($productId, array $keywords): string
    {
        if (! $productId) {
            return '';
        }

        $product = \App\Models\Product::with(['specValues.productSpecGroup', 'specValues.productSpecItem'])->find($productId);

        if (! $product) {
            return '';
        }

        $cpu = '';
        $gpu = '';
        $ram = '';
        $resolution = '';
        $mah = '';
        $charging = '';
        $screenSize = '';
        
        // Camera specific
        $mainCamera = '';
        $cameraFeatures = '';

        foreach ($product->spec_groups as $group) {
            foreach ($group->items as $item) {
                // Check for CPU/Processor/Chipset
                if (stripos($item->key, 'Chipset') !== false || stripos($item->key, 'Processor') !== false || stripos($item->key, 'CPU') !== false) {
                    if (empty($cpu)) $cpu = $item->value;
                }
                // Check for GPU
                if (stripos($item->key, 'GPU') !== false) {
                    if (empty($gpu)) $gpu = $item->value;
                }
                // Check for RAM
                if (stripos($item->key, 'RAM') !== false || stripos($item->key, 'Memory') !== false) {
                     if (empty($ram)) $ram = $item->value;
                }
                // Check for Resolution
                if (stripos($item->key, 'Resolution') !== false) {
                    if (empty($resolution)) $resolution = $item->value;
                }
                // Check for Battery/mAh
                if (stripos($item->key, 'Type') !== false && stripos($group->name, 'Battery') !== false) {
                     if (empty($mah)) $mah = $item->value;
                }
                // Check for Charging
                if (stripos($item->key, 'Charging') !== false) {
                    if (empty($charging)) $charging = $item->value;
                }
                // Check for Screen Size
                if (stripos($item->key, 'Size') !== false && stripos($group->name, 'Display') !== false) {
                    if (empty($screenSize)) $screenSize = $item->value;
                }
                
                // Check for Camera
                if (stripos($group->name, 'Camera') !== false) {
                    if (stripos($item->key, 'Main') !== false || stripos($item->key, 'Triple') !== false || stripos($item->key, 'Dual') !== false || stripos($item->key, 'Quad') !== false || stripos($item->key, 'Modules') !== false) {
                        if (empty($mainCamera)) $mainCamera = $item->value;
                    }
                    if (stripos($item->key, 'Features') !== false) {
                        if (empty($cameraFeatures)) $cameraFeatures = $item->value;
                    }
                }
            }
        }

        // Check if the keywords imply Gaming/Performance.
        $isGaming = false;
        $isBattery = false;
        $isCamera = false;
        
        foreach ($keywords as $k) {
            if (in_array($k, ['Performance', 'Platform', 'Processor', 'GPU'])) {
                $isGaming = true;
            }
            if (in_array($k, ['Battery', 'Charging'])) {
                $isBattery = true;
            }
            if (in_array($k, ['Camera', 'Video'])) {
                $isCamera = true;
            }
        }

        // Only return formatted string if we found relevant data, otherwise return empty or partial
        $text = "";
        
        if ($isBattery) {
            $text .= "mAh: " . $mah . "\n";
            $text .= "Screen Size: " . $screenSize . "\n";
            $text .= "Resolution: " . $resolution . "\n";
            $text .= "CPU: " . $cpu . "\n";
            return trim($text);
        }

        if ($isGaming) {
            $text .= "CPU: " . $cpu . "\n";
            $text .= "GPU: " . $gpu . "\n";
            $text .= "RAM: " . $ram . "\n";
            $text .= "Resolution: " . $resolution . "\n";
            return trim($text);
        }
        
        if ($isCamera) {
            $text .= "Main Camera: " . $mainCamera . "\n";
            
            // Extract and display specific fields for clarity
            $mp = '';
            $sensor = '';
            $aperture = '';
            $stabilization = 'None';

            if (preg_match('/(\d{2,3})\s*MP/i', $mainCamera, $m)) $mp = $m[1] . " MP";
            if (preg_match('/1\/(\d+(\.\d+)?)"/i', $mainCamera, $m)) $sensor = $m[0];
            if (preg_match('/f\/(\d+(\.\d+)?)/i', $mainCamera, $m)) $aperture = $m[0];
            
            $ois = stripos($mainCamera, 'OIS') !== false || stripos($cameraFeatures, 'OIS') !== false;
            $eis = stripos($mainCamera, 'EIS') !== false || stripos($cameraFeatures, 'EIS') !== false;
            
            if ($ois) $stabilization = 'OIS';
            elseif ($eis) $stabilization = 'EIS';

            $text .= "Megapixel: " . $mp . "\n";
            $text .= "Sensor Size: " . $sensor . "\n";
            $text .= "Aperture: " . $aperture . "\n";
            $text .= "Stabilization: " . $stabilization . "\n";
            
            return trim($text);
        }

        if ($cpu) $text .= "CPU: " . $cpu . "\n";
        if ($gpu) $text .= "GPU: " . $gpu . "\n";
        if ($ram) $text .= "RAM: " . $ram . "\n";
        if ($resolution) $text .= "Resolution: " . $resolution . "\n";

        // Fallback for Battery/Camera sections (keep existing logic for them)
        foreach ($product->spec_groups as $group) {
            $matches = false;
            foreach ($keywords as $keyword) {
                if (stripos($group->name, $keyword) !== false) {
                    $matches = true;
                    break;
                }
            }

            if ($matches) {
                $text .= $group->name . ":\n";
                foreach ($group->items as $item) {
                    $text .= "  - " . $item->key . ": " . $item->value . "\n";
                }
                $text .= "\n";
            }
        }
        return trim($text);
    }
}
