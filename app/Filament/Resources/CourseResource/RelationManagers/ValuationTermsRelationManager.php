<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ValuationTermsRelationManager extends RelationManager
{
    protected static string $relationship = 'valuationTerms';

    public function form(Form $form): Form
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('term')
            ->columns([
                Tables\Columns\TextColumn::make('term'),
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->disabled(auth()->user()->hasRole('Student')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn ($record) => '/admin/valuation-terms/'.$record->id.'/edit')
                    ->visible(! auth()->user()->hasRole('Student')),
                Tables\Actions\DeleteAction::make()->visible(! auth()->user()->hasRole('Student')),
                Tables\Actions\ViewAction::make()->url(fn ($record) => '/admin/valuation-terms/'.$record->id),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->disabled(auth()->user()->hasRole('Student')),
                ]),
            ]);
    }
}
