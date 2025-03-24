<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TransaksiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiResource\RelationManagers;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static ?string $navigationLabel = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Select::make('produk_id') // Ubah dari 'product_id' ke 'produk_id'
                    ->label('Product')
                    ->relationship('produk', 'nama')
                    ->searchable()
                    ->required()
                    ->afterStateUpdated(function ($state, $set, $get) {
                        self::updateTotalPrice($set, $get);
                    }),

                TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->required()
                    ->live() // Tambahkan agar otomatis menghitung saat quantity berubah
                    ->afterStateUpdated(function ($state, $set, $get) {
                        self::updateTotalPrice($set, $get);
                    }),


                TextInput::make('total_price')
                    ->label('Total Price')
                    ->numeric()
                    ->disabled()
                    ->required(),

                Radio::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Success',
                        'failed' => 'Failed',
                    ])
                    ->default('pending')
                    ->required(),

                Radio::make('payment')
                    ->label('Payment Method')
                    ->options([
                        'bca' => 'BCA',
                        'bri' => 'BRI',
                        'bni' => 'BNI',
                    ])
                    ->required(),

                FileUpload::make('transfer_poto')
                    ->label('Transfer Proof')
                    ->image()
                    ->directory('payments')
                    ->required(),


            ]);
    }

    protected static function updateTotalPrice($set, $get): void
    {
        $productId = $get('produk_id'); // Sesuaikan dengan nama field yang benar
        $quantity = (int) $get('quantity');

        if (!$productId || !$quantity) {
            $set('total_price', 0);
            return;
        }

        $product = Produk::find($productId);

        if ($product) {
            $set('total_price', $product->harga * $quantity);
        } else {
            $set('total_price', 0);
        }
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_id')->label('Order ID')->searchable(),
                TextColumn::make('user.name')->label('User'),
                TextColumn::make('produk.nama')->label('Product')->searchable()->limit(10),
                TextColumn::make('quantity')->label('Quantity')->sortable(),
                TextColumn::make('total_price')->label('Total Price')->money('IDR'),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'success' => 'success', // Hijau
                        'failed' => 'danger',   // Merah
                        'pending' => 'warning', // Kuning
                    }),
                TextColumn::make('payment')->label('Payment Method'),
                ImageColumn::make('transfer_poto')->label('Transfer Proof'),
                TextColumn::make('created_at')->label('Transaction Date')->date('F j, Y')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter by Status')
                    ->options([
                        'Success' => 'success',
                        'Pending' => 'pending',
                        'Failed' => 'failed',
                    ])
                    ->attribute('status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
