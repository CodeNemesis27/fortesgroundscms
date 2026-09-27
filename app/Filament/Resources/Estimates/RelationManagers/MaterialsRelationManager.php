<?php

namespace App\Filament\Resources\Estimates\RelationManagers;

use App\Filament\Resources\Estimates\Pages\EditEstimate;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MaterialsRelationManager extends RelationManager
{
    protected static string $relationship = 'materials';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass !== EditEstimate::class;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('quantity')
            ->columns([
                TextColumn::make('quantity'),
                TextColumn::make('material.name'),
                TextColumn::make('unit_cost')
                    ->money('PHP'),
                TextColumn::make('subtotal')
                    ->money('PHP'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                //
            ]);
    }
}
