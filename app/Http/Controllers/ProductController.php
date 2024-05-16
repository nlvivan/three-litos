<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $products = Product::query()
            ->with('stock')
            ->search($request->search)
            ->paginate(12);

        return Inertia::render('Products/Index', [
            'products' => $products,
            'filters' => $request->only('search'),
        ]);
    }
}
