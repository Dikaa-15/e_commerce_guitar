<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Transaksi;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class LatestTransactions extends TableWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Transaksi::latest()->limit(5)) // Ambil 5 transaksi terbaru
            ->columns([
                TextColumn::make('order_id')
                    ->label('Order ID')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('User'),

                TextColumn::make('produk.nama')
                    ->label('Product')
                    ->searchable()
                    ->limit(10),

                TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),

                TextColumn::make('total_price')
                    ->label('Total Price')
                    ->money('IDR'), // Format uang IDR

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'success' => 'success', // Hijau
                        'failed' => 'danger',   // Merah
                        'pending' => 'warning', // Kuning
                    }),

                TextColumn::make('payment')
                    ->label('Payment Method'),

                ImageColumn::make('transfer_poto')
                    ->label('Transfer Proof'),

                TextColumn::make('created_at')
                    ->label('Transaction Date')
                    ->date('F j, Y') // Format tanggal "Minggu, 1 Januari 2025"
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
