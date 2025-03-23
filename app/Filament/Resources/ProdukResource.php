<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Produk;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProdukResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProdukResource\RelationManagers;
use Filament\Tables\Columns\ImageColumn;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama')
                    ->required(),
                TextInput::make('deskripsi')
                    ->label('Deskripsi')
                    ->required(),
                TextInput::make('harga')
                    ->label('Harga')
                    ->required(),
                FileUpload::make('foto')
                    ->label('Foto')
                    ->directory('produks')
                    ->required(),
                TextInput::make('stok')
                    ->label('Stok')
                    ->required(),
                Select::make('category')
                    ->label('Category')
                    ->options([
                        'Elektrik' => 'Elektrik',
                        'Akustik' => 'Akustik',
                        'Bass' => 'Bass',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable()
                    ->limit(10)
                    ->sortable(),
                ImageColumn::make('foto')
                        ->searchable()
                        ->sortable(),
                TextColumn::make('deskripsi')
                    ->searchable()
                    ->limit(10)
                    ->sortable(),
                TextColumn::make('harga')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stok')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}
