<?php

namespace App\Filament\Resources\CustomDeadlineResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomDeadlineUserPivotRelationManager extends RelationManager
{
    protected static string $relationship = 'customDeadlineUserPivot';

    protected static ?string $title = 'Custom Deadline';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('applied')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('applied')
            ->columns([
                Tables\Columns\TextColumn::make('student_full_name'),
                Tables\Columns\TextColumn::make('student_email'),
                Tables\Columns\CheckboxColumn::make('applied'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
