<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductSpecItem;
use App\Models\ProductSpecValue;
use Illuminate\Support\Facades\DB;

class CleanupDuplicateSpecs extends Command
{
    protected $signature = 'cleanup:duplicate-specs';
    protected $description = 'Merge duplicate product spec items based on label and group';

    public function handle()
    {
        $this->info('Starting cleanup...');

        // Find duplicates (using collection to be safer with whitespace/case)
        $allItems = ProductSpecItem::all();
        
        // Group ONLY by label to find duplicates even if they are in different groups (if that's desired)
        // OR if the user meant they are in the same group but the previous code didn't catch it.
        // Looking at the screenshot, they seem to be in different groups (1 and 17).
        // Group 1 has 'operating-system-1', Group 17 has 'operating-system-3'.
        // Wait, the screenshot shows Group 1 has 'operating-system-1' AND 'operating-system-2'.
        // And Group 7 has 'operating-system'.
        
        // Let's group by label ONLY to merge ALL "Operating System" items into one global definition?
        // Or should we merge per group?
        // The user said "product spec items have duplicate delete now".
        // If they are in different groups (e.g. "Platform" vs "Features"), maybe they should stay?
        // But if the user wants to clean up the mess from the scraping, likely they want to merge them.
        
        // Let's try to group by label AND group_id first, but maybe the previous run didn't work because of hidden characters?
        // Actually, let's debug what we have.
        
        // But if the user sees duplicates in the screenshot, let's look closely.
        // ID 11: group 1, label "Operating System"
        // ID 13: group 1, label "Operating System"
        // They are in the same group!
        
        // Why did the previous code find 0 sets?
        // Maybe 'Operating System' vs 'Operating System ' (trailing space)?
        // I added trim() in the previous step.
        
        // Let's force a more aggressive cleanup.
        $duplicates = $allItems->groupBy(function ($item) {
            // Normalize aggressively: lowercase, remove all non-alphanumeric characters except spaces
            $label = preg_replace('/[^a-z0-9 ]/', '', strtolower($item->label));
            return $item->product_spec_group_id . '|' . trim($label);
        })->filter(function ($group) {
            return $group->count() > 1;
        });

        $this->info("Found {$duplicates->count()} sets of duplicates.");

        foreach ($duplicates as $key => $items) {
            $parts = explode('|', $key);
            $groupId = $parts[0];
            $label = $parts[1]; // normalized label
            
            $this->info("Processing: Group {$groupId} - Label '{$label}'");

            // Keep the first one (master)
            $master = $items->first();
            $toDelete = $items->slice(1);

            foreach ($toDelete as $item) {
                // Update values to point to master
                ProductSpecValue::where('product_spec_item_id', $item->id)
                    ->update(['product_spec_item_id' => $master->id]);
                
                // Delete the duplicate item
                $item->delete();
                $this->info("  Merged item ID {$item->id} into {$master->id}");
            }
        }

        $this->info('Cleanup complete.');
    }
}
