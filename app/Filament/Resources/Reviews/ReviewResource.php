<?php

namespace App\Filament\Resources\Reviews;

use App\Filament\Resources\Reviews\Pages\CreateReview;
use App\Filament\Resources\Reviews\Pages\EditReview;
use App\Filament\Resources\Reviews\Pages\ListReviews;
use App\Filament\Resources\Reviews\Schemas\ReviewForm;
use App\Filament\Resources\Reviews\Tables\ReviewsTable;
use App\Models\Review;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;
    protected static string|\UnitEnum|null $navigationGroup = 'User Interaction';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReviewsTable::configure($table);
    }

    public static function infolist(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Review Details')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('product.title')->label('Product'),
                        \Filament\Infolists\Components\TextEntry::make('name')->label('Reviewer'),
                        \Filament\Infolists\Components\TextEntry::make('rating_design')->label('Design Rating'),
                        \Filament\Infolists\Components\TextEntry::make('rating_performance')->label('Performance Rating'),
                        \Filament\Infolists\Components\TextEntry::make('rating_camera')->label('Camera Rating'),
                        \Filament\Infolists\Components\TextEntry::make('rating_battery')->label('Battery Rating'),
                        \Filament\Infolists\Components\TextEntry::make('review')->columnSpanFull(),
                        \Filament\Infolists\Components\TextEntry::make('pros')->listWithLineBreaks()->bulleted(),
                        \Filament\Infolists\Components\TextEntry::make('cons')->listWithLineBreaks()->bulleted(),
                    ])->columns(2),
                \Filament\Schemas\Components\Section::make('User Info')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('ip_address')->copyable(),
                        \Filament\Infolists\Components\TextEntry::make('finger_print'),
                        \Filament\Infolists\Components\IconEntry::make('is_approve')->boolean()->label('Approved'),
                        \Filament\Infolists\Components\IconEntry::make('is_ip_banned')->boolean()->label('IP Banned'),
                    ])->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReviews::route('/'),
            //'create' => CreateReview::route('/create'),
            //'edit' => EditReview::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_approve', false)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('is_approve', false)->exists() ? 'danger' : 'primary';
    }
}
