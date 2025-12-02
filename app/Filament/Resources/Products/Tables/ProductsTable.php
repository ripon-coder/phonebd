<?php

namespace App\Filament\Resources\Products\Tables;

use App\Filament\Resources\ProductPerformances\ProductPerformanceResource;
use App\Filament\Resources\AntutuScores\AntutuScoreResource;
use App\Filament\Resources\ProductFaqs\ProductFaqResource;
use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('base_price')
                    ->money('bdt'),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('short_description')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('meta_title')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('meta_description')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('meta_keywords')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('meta_image')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->recordActions([
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
                ViewAction::make(),
                EditAction::make(),
                Action::make('performance')
                    ->label('Performance')
                    ->icon('heroicon-o-chart-bar')
                    ->url(fn (Product $record): string => 
                        $record->productPerformance 
                            ? ProductPerformanceResource::getUrl('edit', ['record' => $record->productPerformance]) 
                            : ProductPerformanceResource::getUrl('create', ['product_id' => $record->id])
                    )->openUrlInNewTab(),

                Action::make('antutu')
                    ->label('Antutu')
                    ->icon('heroicon-o-fire')
                    ->color('danger')
                    ->url(fn (Product $record): string => 
                        $record->antutuScore 
                            ? AntutuScoreResource::getUrl('edit', ['record' => $record->antutuScore]) 
                            : AntutuScoreResource::getUrl('create', ['product_id' => $record->id])
                    )->openUrlInNewTab(),

                // Action::make('faqs')
                //     ->label('FAQs')
                //     ->icon('heroicon-o-question-mark-circle')
                //     ->color('success')
                //     ->url(function (Product $record): string {
                //         $count = $record->faqs()->count();
                //         if ($count === 0) {
                //             return ProductFaqResource::getUrl('create', ['product_id' => $record->id]);
                //         } elseif ($count === 1) {
                //             return ProductFaqResource::getUrl('edit', ['record' => $record->faqs()->first()]);
                //         } else {
                //             return ProductFaqResource::getUrl('index', ['tableFilters' => ['product' => ['value' => $record->id]]]);
                //         }
                //     })->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    RestoreBulkAction::make(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
