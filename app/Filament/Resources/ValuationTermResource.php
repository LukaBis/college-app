<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ValuationTermResource\Pages;
use App\Filament\Resources\ValuationTermResource\RelationManagers\ValuationsRelationManager;
use App\Models\ValuationTerm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ValuationTermResource extends Resource
{
    protected static ?string $model = ValuationTerm::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('term')
                    ->required()
                    ->label('Deadline')
                    ->disabled(auth()->user()->hasRole('Student')),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->hint('For example: Deadline1 or Deadline2')
                    ->disabled(auth()->user()->hasRole('Student')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            ValuationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListValuationTerms::route('/'),
            'create' => Pages\CreateValuationTerm::route('/create'),
            'edit' => Pages\EditValuationTerm::route('/{record}/edit'),
        ];
    }
}
