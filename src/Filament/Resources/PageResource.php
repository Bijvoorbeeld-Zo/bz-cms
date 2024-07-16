<?php

namespace JornBoerema\BzCMS\Filament\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use JornBoerema\BzCMS\Filament\Resources\PageResource\Pages;
use JornBoerema\BzCMS\Models\Page;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $slug = 'pages';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('created_at')
                    ->label(__('bz-cms::bz-cms.created_at'))
                    ->content(fn(?Page $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                Placeholder::make('updated_at')
                    ->label(__('bz-cms::bz-cms.updated_at'))
                    ->content(fn(?Page $record): string => $record?->updated_at?->diffForHumans() ?? '-'),

                TextInput::make('title')
                    ->label(__('bz-cms::bz-cms.title'))
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->label(__('bz-cms::bz-cms.slug'))
                    ->required()
                    ->unique(Page::class, 'slug', fn($record) => $record),

                Builder::make('elements')
                    ->label(__('bz-cms::bz-cms.elements'))
                    ->columnSpanFull()
                    ->blocks(array_map(function ($item) {
                        $block = new $item();

                        return Builder\Block::make($item)
                            ->label($block->getLabel())
                            ->schema([
                                Grid::make(6)
                                    ->schema([
                                        Fieldset::make(__('bz-cms::bz-cms.group'))
                                            ->columnSpan(2)
                                            ->columns(1)
                                            ->schema([
                                                TextInput::make('group_id')
                                                    ->label(__('bz-cms::bz-cms.group')),

                                                Toggle::make('is_default')
                                                    ->label(__('bz-cms::bz-cms.is_default')),
                                            ]),

                                        Fieldset::make(__('bz-cms::bz-cms.visible'))
                                            ->columnSpan(4)
                                            ->columns(3)
                                            ->schema([
                                                Toggle::make('is_hidden')
                                                    ->inline(false)
                                                    ->label(__('bz-cms::bz-cms.is_hidden')),

                                                Grid::make(1)
                                                    ->columnSpan(2)
                                                    ->schema([
                                                        DateTimePicker::make('visible_from')
                                                            ->label(__('bz-cms::bz-cms.visible_from'))
                                                            ->seconds(false)
                                                            ->inlineLabel(true),

                                                        DateTimePicker::make('visible_until')
                                                            ->label(__('bz-cms::bz-cms.visible_until'))
                                                            ->seconds(false)
                                                            ->inlineLabel(true),
                                                    ]),
                                            ]),
                                    ]),

                                ...$block->form()
                            ]);
                    }, config('bz-cms.blocks'))),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('bz-cms::bz-cms.title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label(__('bz-cms::bz-cms.slug'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }

    public static function getNavigationLabel(): string
    {
        return __('bz-cms::bz-cms.page_resource.nav_label');
    }

    public static function getPluralLabel(): string
    {
        return __('bz-cms::bz-cms.page_resource.plural_label');
    }

    public static function getLabel(): ?string
    {
        return __('bz-cms::bz-cms.page_resource.label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('bz-cms::bz-cms.page_resource.nav_group');
    }

    public static function getNavigationSort(): ?int
    {
        return config('bz-cms.page_resource.nav_sort');
    }
}
