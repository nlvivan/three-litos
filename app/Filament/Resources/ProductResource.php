<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Stock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Product Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('batch_id')
                    ->relationship('batch', 'batch_number')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('item_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('₱'),
                Forms\Components\DatePicker::make('expiry_date'),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image_url')
                    ->label('Image')
                    ->directory('product_images')
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Image'),
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('PHP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->dateTime('Y-m-d')
                    ->sortable(),
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
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('Add Stock')
                        ->icon('heroicon-o-inbox-arrow-down')
                        ->form([
                            Forms\Components\TextInput::make('stock')
                                ->label('Stock')
                                ->numeric()
                                ->required(),
                        ])
                        ->action(function (array $data, Product $record): void {
                            Stock::query()
                                ->updateOrCreate(
                                    ['product_id' => $record->id],
                                    ['stock' => $data['stock'] + $record->stock?->stock]
                                );

                            Notifications\Notification::make()
                                ->title('Stock Added')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->button()
                    ->label('Actions'),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProducts::route('/'),
        ];
    }
}
