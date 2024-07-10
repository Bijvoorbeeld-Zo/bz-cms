<?php

namespace JornBoerema\BzCMS\Filament\Resources;

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
}
