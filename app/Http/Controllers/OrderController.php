<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Actions\Action;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $orders = $user->orders()->get();

        return Inertia::render('Orders/Index', [
            'orders' => $orders
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'carts' => 'required|array',
            'carts.*.id' => 'required|exists:carts,id',
            'carts.*.product_id' => 'required|exists:products,id',
            'carts.*.quantity' => 'required|gt:0',
            'time_to_pick_up' => 'required',
        ]);

        $user = auth()->user();
        $users = User::role(['Staff', 'Admin'])->get();
        $dateTime = Carbon::createFromFormat('H:i', data_get($data, 'time_to_pick_up'));
        $dateTime->setDate(now()->year, now()->month, now()->day);

        $order = Order::create([
            'order_number' => Str::random(10),
            'user_id' => $user->id,
            'total_amount' => 0,
            'time_to_pick_up' => $dateTime,
            'status' => 'pending',
        ]);

        $orderItems = [];
        $totalAmount = 0;

        foreach ($data['carts'] as $orderItem) {
            $product = Product::findOrFail($orderItem['product_id']);
            $productStock = $product?->stock?->stock;

            $amount = $orderItem['quantity'] * $product->price;

            $orderItems[] = [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $orderItem['quantity'],
                'total_amount' => $amount,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $totalAmount += $amount;

            $product->stock()->update([
                'stock' => $productStock - $orderItem['quantity'],
            ]);
           

            $product = $product->fresh();

            if($product->stock->stock < $product->stock->critical_stock) {
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

            Cart::find($orderItem['id'])->delete();
        }

        $order->orderItems()->insert($orderItems);
        $order->update([
            'total_amount' => $totalAmount,
        ]);

        

        \Filament\Notifications\Notification::make()
            ->title("New Order received ({$user->name})")
            ->icon('heroicon-o-exclamation-circle')
            ->actions([
                Action::make('markAsUnread')
                    ->button()
                    ->markAsUnread(),
            ])
            ->sendToDatabase($users);

        return redirect()->back();
    }

    public function show(Order $order)
    {
        return Inertia::render('Orders/View', [
            'order' => $order->load(['orderItems', 'orderItems.product'])
        ]);
    }
}
