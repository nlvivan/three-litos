<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductOverview extends BaseWidget
{

    protected static ?int $sort = 2;
    protected function getStats(): array
    {
        return [
            Stat::make('Product Daily Purchased', '10'),
            Stat::make('Product Weekly Purchased', '42'),
            Stat::make('Product Monthly Purchased', '400'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole('Admin');
    }
}
