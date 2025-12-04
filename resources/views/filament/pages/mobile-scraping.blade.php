<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}
        <br>
        <div class="mt-4">
            <x-filament::button type="submit">
                Start Scraping
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
