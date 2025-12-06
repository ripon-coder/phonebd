<?php

namespace App\Filament\Pages;

use App\Models\Brand;
use App\Models\Category;
use App\Services\ScrapingService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class MobileScraping extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.pages.mobile-scraping';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form($form)
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->label('Category')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('brand_id')
                    ->label('Brand')
                    ->options(Brand::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                \Filament\Forms\Components\TextInput::make('title')
                    ->label('Product Title (Optional)')
                    ->placeholder('Override the scraped title...'),
                Textarea::make('urls')
                    ->label('Mobile URLs (One per line)')
                    ->rows(3)
                    ->requiredWithout('raw_html'),
                Textarea::make('raw_html')
                    ->label('Raw HTML or CSV/Text (Optional)')
                    ->rows(10)
                    ->placeholder('Paste the full HTML or CSV/Text of the specs page here if scraping fails...')
                    ->requiredWithout('urls'),
            ])
            ->statePath('data');
    }

    public bool $processing = false;
    public int $total = 0;
    public int $processed = 0;
    public array $queue = [];
    public array $scrapingResults = [];
    public ?string $currentUrl = null;

    public function initScraping()
    {
        $data = $this->form->getState();
        
        $this->queue = [];
        $rawHtml = $data['raw_html'] ?? null;
        
        if (!empty($rawHtml)) {
            $url = trim(explode("\n", $data['urls'] ?? '')[0] ?? 'manual-submission');
            if (empty($url)) $url = 'manual-submission';
            
            $this->queue[] = [
                'type' => 'raw',
                'url' => $url,
                'content' => $rawHtml,
                'cat' => $data['category_id'],
                'brand' => $data['brand_id'],
                'title' => $data['title'] ?? null,
            ];
        } else {
            $lines = explode("\n", $data['urls']);
            foreach ($lines as $line) {
                $u = trim($line);
                if (!empty($u)) {
                    $this->queue[] = [
                        'type' => 'url',
                        'url' => $u,
                        'cat' => $data['category_id'],
                        'brand' => $data['brand_id'],
                        'title' => $data['title'] ?? null,
                    ];
                }
            }
        }

        $this->total = count($this->queue);
        $this->processed = 0;
        $this->scrapingResults = [];
        $this->processing = true;
        
        if ($this->total === 0) {
            $this->processing = false;
            Notification::make()->title('No URLs found')->warning()->send();
            return 0;
        }

        return $this->total;
    }

    public function processNext()
    {
        if (empty($this->queue)) {
            $this->processing = false;
            $this->finalizeScraping();
            return false;
        }

        $item = array_shift($this->queue);
        $this->currentUrl = $item['url'];
        
        try {
            set_time_limit(120); 
            $service = app(ScrapingService::class);
            
            if ($item['type'] === 'raw') {
                $result = $service->scrapeAndSave($item['url'], $item['cat'], $item['brand'], $item['content'], $item['title']);
            } else {
                $result = $service->scrapeAndSave($item['url'], $item['cat'], $item['brand'], null, $item['title']);
            }
        } catch (\Exception $e) {
            $result = ['status' => 'error', 'message' => "Error processing {$item['url']}: " . $e->getMessage()];
            \Log::error('Scraping Error: ' . $e->getMessage());
        }
        
        $this->scrapingResults[] = $result;
        $this->processed++;
        
        // Return true to continue, unless queue is now empty
        return count($this->queue) > 0;
    }

    public function finalizeScraping()
    {
        $this->processing = false;
        $this->currentUrl = null;

        $results = $this->scrapingResults;
        $successCount = collect($results)->where('status', 'success')->count();
        $skippedCount = collect($results)->where('status', 'skipped')->count();
        $errorCount = collect($results)->where('status', 'error')->count();

        Notification::make()
            ->title('Scraping Completed')
            ->body("Success: $successCount, Skipped: $skippedCount, Errors: $errorCount")
            ->success()
            ->persistent()
            ->send();
            
        if ($errorCount > 0) {
             $errors = collect($results)->where('status', 'error')->pluck('message')->join("\n");
             if (strlen($errors) > 5000) {
                 $errors = substr($errors, 0, 5000) . '... (truncated)';
             }
             
             Notification::make()
                ->title('Scraping Errors')
                ->body($errors)
                ->danger()
                ->persistent()
                ->send();
        }
    }

    // Keep the submit method empty or aliased if needed by interface, but we are using wire:submit="initScraping" in view
    public function submit(): void 
    {
        $this->initScraping();
    }
}
