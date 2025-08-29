<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use App\Models\Category;
use Illuminate\Support\Str;
use Filament\Forms\Set;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Set $set) => $operation === 'create'
                                ? $set('slug', Str::slug($state))
                                : null),
                             
                        TextInput::make('slug')
                            ->maxLength(255)
                            ->disabled()
                            ->required()
                            ->dehydrated()
                            ->unique(Category::class, 'slug', ignoreRecord: true),
                FileUpload::make('image')
                    ->image(),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
