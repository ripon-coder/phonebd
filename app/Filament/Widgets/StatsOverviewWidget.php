<?php

namespace App\Filament\Widgets;

use App\Models\Ad;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalProducts = Product::count();
        $publishedProducts = Product::where('is_published', true)->count();
        
        $totalBlogPosts = BlogPost::count();
        $publishedBlogPosts = BlogPost::where('is_published', true)->count();
        
        $activeAds = Ad::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->count();

        return [
            Stat::make('Total Products', $totalProducts)
                ->description("{$publishedProducts} published")
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
            
            Stat::make('Total Brands', Brand::count())
                ->description('Active brands')
                ->descriptionIcon('heroicon-o-building-storefront')
                ->color('info'),
            
            Stat::make('Total Categories', Category::count())
                ->description('Product categories')
                ->descriptionIcon('heroicon-o-rectangle-stack')
                ->color('warning'),
            
            Stat::make('Blog Posts', $totalBlogPosts)
                ->description("{$publishedBlogPosts} published")
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary'),
            
            Stat::make('Active Ads', $activeAds)
                ->description('Currently running')
                ->descriptionIcon('heroicon-o-megaphone')
                ->color('danger'),
        ];
    }
}
