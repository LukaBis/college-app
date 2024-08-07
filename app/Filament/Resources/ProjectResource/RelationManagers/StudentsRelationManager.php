<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('jmbag'),
                Tables\Columns\ToggleColumn::make('team_lead')->disabled(auth()->user()->hasRole('Student')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->attachAnother(false)
                    ->preloadRecordSelect()
                    ->recordTitle(fn (User $record): string => "{$record->name} {$record->surname} ({$record->email})")
                    ->disabled($this->getOwnerRecord()->max_students <= $this->getOwnerRecord()->students()->count() | auth()->user()->hasRole('Student'))
                    ->label('Add student to this project')
                    ->modalSubmitActionLabel('Add student')
                    ->slideOver(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()->disabled(auth()->user()->hasRole('Student'))->label('Remove from project'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
