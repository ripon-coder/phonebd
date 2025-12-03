<?php

namespace App\Filament\Resources\Reviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('product.title')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->url(fn ($record) => route('product.show', ['category_slug' => $record->product->category->slug, 'product' => $record->product->slug]))
                    ->openUrlInNewTab()
                    ->color('primary'),
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                \Filament\Tables\Columns\TextColumn::make('ip_address')
                    ->searchable()
                    ->copyable(),
                \Filament\Tables\Columns\IconColumn::make('is_approve')
                    ->boolean()
                    ->label('Approved'),

                \Filament\Tables\Columns\TextColumn::make('rating_design')
                    ->label('Ratings')
                    ->formatStateUsing(fn ($record) => "D:{$record->rating_design} P:{$record->rating_performance} C:{$record->rating_camera} B:{$record->rating_battery}"),
                \Filament\Tables\Columns\TextColumn::make('no_spam_rating')
                    ->label('Spam Score')
                    ->sortable()
                    ->color(fn ($state) => $state < 3 ? 'danger' : ($state < 6 ? 'warning' : 'success')),

                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                \Filament\Tables\Filters\TernaryFilter::make('is_approve')
                    ->label('Approval Status'),
                \Filament\Tables\Filters\TernaryFilter::make('is_ip_banned')
                    ->label('Banned Status'),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make('view_details')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Review Details')
                    ->mutateRecordDataUsing(function (array $data, $record) {
                        $data['product_is_review'] = $record->product->is_review;
                        $data['product_review_count_max'] = $record->product->review_count_max;
                        return $data;
                    })
                    ->form([
                        \Filament\Schemas\Components\Section::make('Activity & Settings')
                            ->schema([
                                \Filament\Forms\Components\Placeholder::make('stats')
                                    ->label('')
                                    ->content(function ($record) {
                                        $ip = $record->ip_address;
                                        if (!$ip) return 'No IP';
                                        $approved = \App\Models\Review::where('ip_address', $ip)->where('is_approve', true)->count() 
                                                  + \App\Models\CameraSample::where('ip_address', $ip)->where('is_approve', true)->count();
                                        $declined = \App\Models\Review::where('ip_address', $ip)->where('is_approve', false)->count() 
                                                  + \App\Models\CameraSample::where('ip_address', $ip)->where('is_approve', false)->count();
                                        
                                        $max = $record->product->review_count_max ?? 0;
                                        $current = \App\Models\Review::where('product_id', $record->product_id)->where('ip_address', $ip)->count();
                                        $left = max(0, $max - $current);
        
                                        return new \Illuminate\Support\HtmlString("
                                            <div class='grid grid-cols-3 gap-4 text-center'>
                                                <div>
                                                    <span class='block text-xl font-bold text-primary-600'>{$left}</span>
                                                    <span class='text-sm text-gray-500'>Left</span>
                                                </div>
                                                <div>
                                                    <span class='block text-xl font-bold text-success-600'>{$approved}</span>
                                                    <span class='text-sm text-gray-500'>Approved</span>
                                                </div>
                                                <div>
                                                    <span class='block text-xl font-bold text-danger-600'>{$declined}</span>
                                                    <span class='text-sm text-gray-500'>Declined</span>
                                                </div>
                                            </div>
                                        ");
                                    }),
                                \Filament\Schemas\Components\Group::make([
                                    \Filament\Forms\Components\Toggle::make('product_is_review')
                                        ->label('Enable Reviews'),
                                    \Filament\Forms\Components\TextInput::make('product_review_count_max')
                                        ->label('Max Reviews')
                                        ->numeric(),
                                ]),
                            ])->columns(2),
                        \Filament\Schemas\Components\Section::make('Review Details')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('product.title')->label('Product')->disabled(),
                                \Filament\Forms\Components\TextInput::make('name')->label('Reviewer')->disabled(),
                                \Filament\Forms\Components\TextInput::make('rating_design')->label('Design Rating')->disabled(),
                                \Filament\Forms\Components\TextInput::make('rating_performance')->label('Performance Rating')->disabled(),
                                \Filament\Forms\Components\TextInput::make('rating_camera')->label('Camera Rating')->disabled(),
                                \Filament\Forms\Components\TextInput::make('rating_battery')->label('Battery Rating')->disabled(),
                                \Filament\Forms\Components\Textarea::make('review')->columnSpanFull()->disabled(),
                                \Filament\Forms\Components\Textarea::make('pros')->disabled(),
                                \Filament\Forms\Components\Textarea::make('cons')->disabled(),
                            ])->columns(2),
                        \Filament\Schemas\Components\Section::make('User Info')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('ip_address')->disabled(),
                                \Filament\Forms\Components\TextInput::make('finger_print')->disabled(),
                                \Filament\Forms\Components\Toggle::make('is_approve')->label('Approved')->disabled(),
                                \Filament\Forms\Components\Toggle::make('is_ip_banned')->label('IP Banned')->disabled(),
                            ])->columns(2),
                    ])
                    ->action(function ($record, array $data) {
                        $record->product->update([
                            'is_review' => $data['product_is_review'],
                            'review_count_max' => $data['product_review_count_max'],
                        ]);
                        \Filament\Notifications\Notification::make()->title('Settings Updated')->success()->send();
                    })
                    ->modalFooterActions(fn ($record, $action) => [
                        $action->getModalSubmitAction()->label('Update Settings'),
                        \Filament\Actions\Action::make('approve_modal')
                            ->label('Approve')
                            ->icon('heroicon-o-check-circle')
                            ->color('success')
                            ->action(fn () => $record->update(['is_approve' => true]))
                            ->visible(!$record->is_approve)
                            ->cancelParentActions(),
                        \Filament\Actions\Action::make('decline_modal')
                            ->label('Decline')
                            ->icon('heroicon-o-x-circle')
                            ->color('warning')
                            ->action(fn () => $record->update(['is_approve' => false]))
                            ->visible($record->is_approve)
                            ->cancelParentActions(),
                        \Filament\Actions\Action::make('ban_ip_modal')
                            ->label('Ban IP')
                            ->icon('heroicon-o-no-symbol')
                            ->color('danger')
                            ->requiresConfirmation()
                            ->action(function () use ($record) {
                                if (!$record->ip_address) return;
                                \App\Models\Review::where('ip_address', $record->ip_address)->update(['is_ip_banned' => true]);
                                \App\Models\CameraSample::where('ip_address', $record->ip_address)->update(['is_ip_banned' => true]);
                                \Filament\Notifications\Notification::make()->title('IP Banned')->success()->send();
                            })
                            ->visible(!$record->is_ip_banned)
                            ->cancelParentActions(),
                        \Filament\Actions\Action::make('unban_ip_modal')
                            ->label('Unban IP')
                            ->icon('heroicon-o-check')
                            ->color('success')
                            ->requiresConfirmation()
                            ->action(function () use ($record) {
                                if (!$record->ip_address) return;
                                \App\Models\Review::where('ip_address', $record->ip_address)->update(['is_ip_banned' => false]);
                                \App\Models\CameraSample::where('ip_address', $record->ip_address)->update(['is_ip_banned' => false]);
                                \Filament\Notifications\Notification::make()->title('IP Unbanned')->success()->send();
                            })
                            ->visible($record->is_ip_banned)
                            ->cancelParentActions(),
                    ]),
                //\Filament\Actions\EditAction::make(),
                \Filament\Actions\ActionGroup::make([
                    \Filament\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($record) => $record->update(['is_approve' => true]))
                        ->visible(fn ($record) => !$record->is_approve),
                    \Filament\Actions\Action::make('decline')
                        ->label('Decline')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->action(fn ($record) => $record->update(['is_approve' => false]))
                        ->visible(fn ($record) => $record->is_approve),
                    \Filament\Actions\Action::make('ban_ip')
                        ->label('Ban IP')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            if (!$record->ip_address) return;
                            \App\Models\Review::where('ip_address', $record->ip_address)->update(['is_ip_banned' => true]);
                            \App\Models\CameraSample::where('ip_address', $record->ip_address)->update(['is_ip_banned' => true]);
                            \Filament\Notifications\Notification::make()->title('IP Banned')->success()->send();
                        })
                        ->visible(fn ($record) => !$record->is_ip_banned),
                    \Filament\Actions\Action::make('unban_ip')
                        ->label('Unban IP')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            if (!$record->ip_address) return;
                            \App\Models\Review::where('ip_address', $record->ip_address)->update(['is_ip_banned' => false]);
                            \App\Models\CameraSample::where('ip_address', $record->ip_address)->update(['is_ip_banned' => false]);
                            \Filament\Notifications\Notification::make()->title('IP Unbanned')->success()->send();
                        })
                        ->visible(fn ($record) => $record->is_ip_banned),
                ])
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
