<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\OrderItemsRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Order Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('order_number')
                                    ->hiddenOn('create')
                                    ->disabled()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('user_id')
                                    ->hiddenOn('create')
                                    ->disabled()
                                    ->relationship('user', 'name'),
                                Forms\Components\DateTimePicker::make('time_to_pick_up')
                                    ->hiddenOn('create')
                                    ->disabled(),
                                Forms\Components\TextInput::make('amount_paid')
                                    ->hiddenOn('create')
                                    ->live(debounce: 300)
                                    ->afterStateUpdated(function ($state, Set $set, Get $get, Order $order) {
                                        // if($state < $get('total_amount')) {
                                        //     abort(422, 'Invalid Amount');
                                        // }

                                        $change = $state - $order->totalAmountSum();
                                        $set('change', $change);
                                    })
                                    ->numeric(),
                                Forms\Components\TextInput::make('change')
                                    ->hiddenOn('create')
                                    ->disabled()
                                    ->numeric(),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'completed' => 'Completed',
                                    ]),
                            ]),
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Created at')
                                    ->content(fn (Order $record): ?string => $record->created_at?->diffForHumans()),
                                Forms\Components\Placeholder::make('payments_count')
                                    ->label('Total payments')
                                    ->content(fn (Order $record) => $record->totalAmountSum()),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hiddenOn('create'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time_to_pick_up')
                    ->dateTime('h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount_paid')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrderItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
