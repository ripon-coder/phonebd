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

    public function submit(): void
    {
        $service = app(ScrapingService::class);
        $data = $this->form->getState();
        $urls = explode("\n", $data['urls']);
        $rawHtml = $data['raw_html'] ?? null;
        $title = $data['title'] ?? null;
        
        $results = [];

        if (!empty($rawHtml)) {
            // Manual HTML submission (Process only once)
            // Use the first URL as reference or a placeholder
            $url = trim($urls[0] ?? 'manual-submission');
            if (empty($url)) $url = 'manual-submission';
            
            $results[] = $service->scrapeAndSave($url, $data['category_id'], $data['brand_id'], $rawHtml, $title);
        } else {
            // Batch URL processing
            foreach ($urls as $url) {
                $url = trim($url);
                if (empty($url)) continue;
                
                $results[] = $service->scrapeAndSave($url, $data['category_id'], $data['brand_id'], null, $title);
            }
        }

        $successCount = collect($results)->where('status', 'success')->count();
        $skippedCount = collect($results)->where('status', 'skipped')->count();
        $errorCount = collect($results)->where('status', 'error')->count();

        Notification::make()
            ->title('Scraping Completed')
            ->body("Success: $successCount, Skipped: $skippedCount, Errors: $errorCount")
            ->success()
            ->send();
            
        if ($errorCount > 0) {
             $errors = collect($results)->where('status', 'error')->pluck('message')->join("\n");
             Notification::make()
                ->title('Scraping Errors')
                ->body($errors)
                ->danger()
                ->send();
        }
    }
}
