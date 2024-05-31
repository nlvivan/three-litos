<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Component;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItems';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $product = Product::find($state);
                        $set('price', $product->price);
                    })
                    ->afterStateHydrated(function ($state, Set $set, Get $get) {
                        $product = Product::find($state);
                        if ($product) {
                            $set('price', $product->price);
                        }
                    })
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->disabled()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $totalAmount = $state * $get('price');
                        $set('total_amount', $totalAmount);
                    })
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_amount')
                    ->disabled()
                    ->required()
                    ->maxLength(255),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('quantity')
            ->columns([
                Tables\Columns\ImageColumn::make('product.image_url')
                    ->label('Image'),
                Tables\Columns\TextColumn::make('product.name'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('PHP'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(function (array $data) {

                        $users = User::role(['Staff', 'Admin'])->get();
                        $order = $this->getOwnerRecord();
                        $product = Product::findOrFail($data['product_id']);
                        $productStock = $product?->stock?->stock;

                        $totalAmount = $product->price * $data['quantity'];

                        $order->update([
                            'total_amount' => $order['total_amount'] + $totalAmount,
                        ]);

                        $product->stock()->update([
                            'stock' => $productStock - $data['quantity'],
                        ]);

                        $product = $product->fresh();

                        if ($product->stock->stock < $product->stock->critical_stock) {
                            \Filament\Notifications\Notification::make()
                                ->title("{$product->name} is in low stock")
                                ->warning()
                                ->icon('heroicon-o-exclamation-circle')
                                ->actions([
                                    Action::make('markAsUnread')
                                        ->button()
                                        ->markAsUnread(),
                                ])
                                ->sendToDatabase($users);
                        }

                        return $order->orderItems()->create([
                            'product_id' => $data['product_id'],
                            'quantity' => $data['quantity'],
                            'total_amount' => $totalAmount,
                        ]);
                    })
                    ->after(function (Component $livewire) {
                        $livewire->dispatch('refreshOrder');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Order Item')
                    ->using(function (OrderItem $orderItem, $data) {
                        $users = User::role(['Staff', 'Admin'])->get();
                        $order = $this->getOwnerRecord();
                        $product = $orderItem->product;
                        $productStock = $product?->stock?->stock;

                        if ($orderItem->quantity < $data['quantity']) {

                            $differenceQuantity = $data['quantity'] - $orderItem->quantity;

                            $orderItem->update([
                                'quantity' => $data['quantity'],
                                'total_amount' => $data['quantity'] * $product->price,
                            ]);

                            $orderItem->fresh();

                            $product->stock()->update([
                                'stock' => $productStock - $differenceQuantity,
                            ]);

                            $order->update([
                                'total_amount' => $order->totalAmountSum(),
                            ]);

                            $product = $product->fresh();

                            if ($product->stock->stock < $product->stock->critical_stock) {
                                \Filament\Notifications\Notification::make()
                                    ->title("{$product->name} is in low stock")
                                    ->warning()
                                    ->icon('heroicon-o-exclamation-circle')
                                    ->actions([
                                        Action::make('markAsUnread')
                                            ->button()
                                            ->markAsUnread(),
                                    ])
                                    ->sendToDatabase($users);
                            }

                        } else {
                            $differenceQuantity = $orderItem->quantity - $data['quantity'];

                            $orderItem->update([
                                'quantity' => $data['quantity'],
                                'total_amount' => $data['quantity'] * $product->price,
                            ]);

                            $product->stock()->update([
                                'stock' => $productStock + $differenceQuantity,
                            ]);

                            $order->update([
                                'total_amount' => $order->totalAmountSum(),
                            ]);

                            $orderItem->fresh();

                            $product = $product->fresh();

                            if ($product->stock->stock < $product->stock->critical_stock) {
                                \Filament\Notifications\Notification::make()
                                    ->title("{$product->name} is in low stock")
                                    ->warning()
                                    ->icon('heroicon-o-exclamation-circle')
                                    ->actions([
                                        Action::make('markAsUnread')
                                            ->button()
                                            ->markAsUnread(),
                                    ])
                                    ->sendToDatabase($users);
                            }
                        }

                        return $orderItem;
                    })
                    ->after(function (Component $livewire) {
                        $livewire->dispatch('refreshOrder');
                    }),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Delete Order Item')
                    ->using(function (OrderItem $orderItem) {

                        $users = User::role(['Staff', 'Admin'])->get();
                        $order = $this->getOwnerRecord();
                        $product = $orderItem->product;
                        $productStock = $product?->stock?->stock;

                        $order->update([
                            'total_amount' => $order['total_amount'] - $orderItem->total_amount,
                        ]);

                        $product->stock()->update([
                            'stock' => $productStock + $orderItem->quantity,
                        ]);

                        $product = $product->fresh();

                        if ($product->stock->stock < $product->stock->critical_stock) {
                            \Filament\Notifications\Notification::make()
                                ->title("{$product->name} is in low stock")
                                ->warning()
                                ->icon('heroicon-o-exclamation-circle')
                                ->actions([
                                    Action::make('markAsUnread')
                                        ->button()
                                        ->markAsUnread(),
                                ])
                                ->sendToDatabase($users);
                        }

                        return $orderItem->delete();
                    })
                    ->after(function (Component $livewire) {
                        $livewire->dispatch('refreshOrder');
                    }),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
