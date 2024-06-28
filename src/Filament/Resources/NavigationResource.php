<?php

namespace JornBoerema\BzCMS\Filament\Resources;

use JornBoerema\BzCMS\Filament\Resources\NavigationResource\Pages;
use JornBoerema\BzCMS\Models\Navigation;
use JornBoerema\BzCMS\Models\Page;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class NavigationResource extends Resource
{
    protected static ?string $model = Navigation::class;

    protected static ?string $slug = 'navigations';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->columnSpanFull()
                    ->required(),

                Repeater::make('items')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('title'),

                        Section::make()
                            ->columnSpanFull()
                            ->columns(2)
                            ->schema([
                                Select::make('type')
                                    ->columnSpan(1)
                                    ->options([
                                        'page' => 'Page',
                                        'external' => 'External',
                                        'dropdown' => 'Dropdown'
                                    ])
                                    ->live(),

                                TextInput::make('url')
                                    ->label('URL')
                                    ->visible(fn (Get $get) => $get('type') === 'external'),

                                Select::make('page_id')
                                    ->label('Page')
                                    ->options(Page::all()->pluck('title', 'id'))
                                    ->visible(fn (Get $get) => $get('type') === 'page')
                                    ->searchable(),
                            ]),

                        Repeater::make('children')
                            ->visible(fn (Get $get) => $get('type') === 'dropdown')
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('title'),

                                Section::make()
                                    ->columnSpanFull()
                                    ->columns(2)
                                    ->schema([
                                        Select::make('type')
                                            ->columnSpan(1)
                                            ->options([
                                                'page' => 'Page',
                                                'external' => 'External'
                                            ])
                                            ->live(),

                                        TextInput::make('url')
                                            ->label('URL')
                                            ->visible(fn (Get $get) => $get('type') === 'external'),

                                        Select::make('page_id')
                                            ->label('Page')
                                            ->options(Page::all()->pluck('title', 'id'))
                                            ->visible(fn (Get $get) => $get('type') === 'page')
                                            ->searchable(),
                                    ]),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
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
            'index' => Pages\ListNavigations::route('/'),
            'create' => Pages\CreateNavigation::route('/create'),
            'edit' => Pages\EditNavigation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
