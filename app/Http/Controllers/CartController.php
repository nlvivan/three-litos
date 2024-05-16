<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index()
    {
        return Inertia::render('Carts/Index');
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
