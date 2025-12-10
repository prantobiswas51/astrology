<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatus extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $users = \App\Models\User::count();
        $pending_orders = \App\Models\Order::where('status', 'Pending')->count();
        $payment_unpaid = \App\Models\Order::where('order_status', 'Unpaid')->count();
        $total_sales = \App\Models\Order::where('status', 'Paid')->sum('total_amount');

        return [
            Stat::make('Pending Orders', $pending_orders),
            Stat::make('Unpaid Payments', $payment_unpaid), 
            Stat::make('Total Users', $users),
            Stat::make('Total Sales', '$' . number_format($total_sales, 2)),
        ];
    }
}
