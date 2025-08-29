<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('street_address')
                    ->required(),
                TextInput::make('city'),
                TextInput::make('state'),
                TextInput::make('zip_code'),
                TextInput::make('country')
                    ->required()
                    ->default('Philippines'),
                Toggle::make('is_registered')
                    ->required(),
            ]);
    }
}
