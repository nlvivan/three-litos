<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $carts = Cart::query()
            ->with('product')
            ->where('user_id', $user->id)
            ->get();

        return Inertia::render('Carts/Index', [
            'carts' => $carts,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer',
        ]);

        $user = auth()->user();

        $user->carts()->create($data);

        return redirect()->back();
    }
}
