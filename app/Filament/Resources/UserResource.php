<?php

namespace App\Filament\Resources;


use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Filament\Forms\Components\Select;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->placeholder('John Doe'),
                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->placeholder('Your email'),
                    TextInput::make('password')
                    ->label('Password')
                    ->required()
                    ->placeholder('Your password'),
                FileUpload::make('foto_profil')
                    ->label('Avatar')
                    ->image()
                    ->directory('profile')
                    ->required()
                    ->placeholder('Your avatar'),
                TextInput::make('alamat')
                    ->label('Address')
                    ->required()
                    ->placeholder('Your address'),
                TextInput::make('no_telepon')
                    ->label('Phone Number')
                    ->required()
                    ->placeholder('Your phone number'),
                Select::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'admin',
                        'user' => 'user',
                    ])
                    ->required()
                    ->placeholder('Select role'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label('Role')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('foto_profil')
                    ->label('Avatar')
                    ->circular()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
