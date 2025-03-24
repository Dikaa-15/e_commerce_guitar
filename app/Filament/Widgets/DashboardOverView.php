<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\User;
use App\Models\Produk;
use App\Models\Transaksi;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class DashboardOverview extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Users', User::count())
                ->icon('heroicon-o-users')
                ->color('primary'),

            Card::make('Total Produk', Produk::count())
                ->icon('heroicon-o-shopping-bag')
                ->color('success'),

            Card::make('Total Pendapatan', 'Rp ' . number_format(Transaksi::sum('total_price'), 0, ',', '.'))
                ->icon('heroicon-o-currency-dollar')
                ->color('warning'),
        ];
    }
}
