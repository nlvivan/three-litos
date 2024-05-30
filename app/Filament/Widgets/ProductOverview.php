<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductOverview extends BaseWidget
{

    protected static ?int $sort = 0;
    protected function getStats(): array
    {
        return [
            Stat::make('Products', Product::query()->count()),
            Stat::make('Customers', Customer::query()->count()),
            Stat::make('Pending Orders', Order::query()->where('status', 'pending')->count()),
        ];
    }
}
