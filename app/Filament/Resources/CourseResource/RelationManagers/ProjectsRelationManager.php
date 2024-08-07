<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Tables\Columns\MyProjectColumn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'projects';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('max_students')
                    ->required()
                    ->numeric()
                    ->label('Max Number of Students'),
            ]);
    }

    public function table(Table $table): Table
    {
        $actions = [];

        $adminActions = [
            Tables\Actions\EditAction::make()
                ->url(fn ($record) => '/admin/projects/'.$record->id.'/edit')
                ->label('More')->icon(null),
            Tables\Actions\DeleteAction::make(),
        ];

        $studentActions = [
            Tables\Actions\ViewAction::make()->url(fn ($record) => '/admin/projects/'.$record->id),
        ];

        if (auth()->user()->hasRole('Course Admin')) {
            $actions = $adminActions;
        }

        if (auth()->user()->hasRole('Student')) {
            $actions = $studentActions;
        }

        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                MyProjectColumn::make('id')->label(''),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions($actions)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
