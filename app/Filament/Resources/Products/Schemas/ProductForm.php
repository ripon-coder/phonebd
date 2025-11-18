<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use App\Models\ProductSpecItem;
use App\Models\ProductSpecGroup;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Wizard;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;



class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Wizard::make([

                /**
                 * STEP 1: BASIC DETAILS
                 */
                Step::make('Basic Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state)))
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpan(2),

                        Forms\Components\Select::make('brand_id')
                            ->options(Brand::pluck('name', 'id'))
                            ->label('Brand')
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('category_id')
                            ->options(Category::pluck('name', 'id'))
                            ->label('Category')
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'official' => 'Official',
                                'unofficial' => 'Unofficial',
                                'upcoming' => 'Upcoming',
                                'discontinued' => 'Discontinued'
                            ])
                            ->label('Product Status')
                            ->required()
                            ->default('official'),

                        Forms\Components\TextInput::make('base_price')
                            ->numeric()
                            ->label('Base Price')
                            ->prefix('৳')
                            ->required(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Main Product Image')
                            ->image()
                            ->directory('products')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('Upload main product image (Max: 2MB)')
                            ->columnSpan(2),

                        Forms\Components\Textarea::make('short_description')
                            ->rows(4)
                            ->columnSpan(2),

                        Forms\Components\Toggle::make('is_published')
                            ->label('Published')
                            ->default(false)
                            ->columnSpan(2),

                        Forms\Components\Toggle::make('is_raw_html')
                            ->label('Raw HTML')
                            ->default(false)
                            ->live()
                            ->columnSpan(2),

                        RichEditor::make('raw_html')
                            ->label('HTML Content')
                            ->columnSpan(2)
                            ->visible(fn(callable $get) => $get('is_raw_html') === true),
                    ])
                    ->columns(2),

                /**
                 * STEP 2: SPECIFICATIONS
                 */
                Step::make('Specifications')
                    ->schema([

                        Forms\Components\Select::make('spec_group_selector')
                            ->label('Select Specification Groups')
                            ->options(ProductSpecGroup::pluck('name', 'id'))
                            ->searchable()
                            ->multiple()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {

                                if (empty($state)) return;

                                $selectedGroups = is_array($state) ? $state : [$state];

                                $existing = $get('specValues') ?? [];

                                foreach ($selectedGroups as $groupId) {

                                    // Skip if already added
                                    $group = ProductSpecGroup::find($groupId);
                                    if (!$group) continue;

                                    $already = collect($existing)
                                        ->pluck('group_name')
                                        ->contains($group->name);

                                    if ($already) continue;

                                    // Load spec items of this group
                                    $specItems = ProductSpecItem::where('product_spec_group_id', $groupId)
                                        ->orderBy('sort_order')
                                        ->get();

                                    // Add group + items
                                    $existing[] = [
                                        'group_name' => $group->name,
                                        'items' => $specItems->map(fn($item) => [
                                            'product_spec_item_id' => $item->id,
                                            '_label' => $item->label,
                                            'value' => null,
                                        ])->toArray(),
                                    ];
                                }

                                // Force refresh
                                $set('specValues', []);
                                $set('specValues', array_values($existing));

                                // Reset dropdown
                                //$set('spec_group_selector', null);
                            })
                            ->helperText('Select multiple groups to load all their specification items.')
                            ->columnSpan(2),


                        // -------------------------
                        // WRAP REPEATER IN GROUP
                        // -------------------------
                        Group::make()
                            ->schema([
                                Forms\Components\Repeater::make('specValues')
                                    ->label('')
                                    ->itemLabel(fn(array $state) => $state['group_name'] ?? '')
                                    ->schema([

                                        Forms\Components\Hidden::make('group_name'),

                                        Forms\Components\Repeater::make('items')
                                            ->label('Specification Items')
                                            ->schema([

                                                Forms\Components\Hidden::make('product_spec_item_id'),

                                                Forms\Components\TextInput::make('_label')
                                                    ->label('Specification')
                                                    ->readOnly()
                                                    ->dehydrated(false),

                                                Forms\Components\TextInput::make('value')
                                                    ->label('Value')
                                                    ->nullable(),

                                            ])
                                            ->columns(2)
                                            ->dehydrated()   
                                            ->columnSpanFull()
                                            ->extraAttributes(['class' => 'pt-1 pb-1'])

                                    ])
                                    ->defaultItems(0)
                                    ->addable(false)
                                    ->reorderable(true)
                                    ->columnSpan(2),
                            ])
                            ->columnSpan(2),


                    ])->columns(2),

                /**
                 * STEP 3: PRODUCT VARIANTS
                 */
                Step::make('Product Variants')
                    ->schema([
                        Forms\Components\Repeater::make('variantPrices')
                            ->relationship('variantPrices')
                            ->label('Variants')
                            ->schema([
                                Forms\Components\TextInput::make('ram')
                                    ->label('RAM')
                                    ->placeholder('8GB')
                                    ->required(),

                                Forms\Components\TextInput::make('storage')
                                    ->label('Storage')
                                    ->placeholder('128GB')
                                    ->required(),

                                Forms\Components\TextInput::make('amount')
                                    ->numeric()
                                    ->label('Price')
                                    ->prefix('৳')
                                    ->required(),

                                Forms\Components\TextInput::make('currency')
                                    ->default('BDT')
                                    ->disabled(),
                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->addActionLabel('Add Another Variant')
                            ->columnSpan(2),
                    ])
                    ->columns(2),

                /**
                 * STEP 4: SEO METADATA
                 */
                Step::make('SEO Metadata')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(60)
                            ->helperText('50–60 characters recommended.'),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('150–160 characters recommended.'),

                        Forms\Components\TagsInput::make('meta_keywords')
                            ->placeholder('Add keywords...'),

                        Forms\Components\FileUpload::make('meta_image')
                            ->label('Social Media Preview Image')
                            ->image()
                            ->directory('seo')
                            ->maxSize(1024),
                    ])
                    ->columns(2),

            ])
                ->columnSpanFull()
                ->skippable()
                ->persistStepInQueryString(),
        ]);
    }
}
