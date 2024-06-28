<?php

namespace App\Filament\Resources\ValuationTermResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ValuationsRelationManager extends RelationManager
{
    protected static string $relationship = 'valuations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('valuation_term_id')
                    ->relationship('valuationTerm', 'title')
                    ->default($this->getOwnerRecord()->id)
                    ->disabled()
                    ->required(),
                Forms\Components\Select::make('student_evaluator_id')
                    ->relationship('evaluator', 'name')
                    ->default(auth()->user()->id)
                    ->disabled()
                    ->required(),
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('rated_student_id', null))
                    ->required(),
                Forms\Components\Select::make('rated_student_id')
                    ->relationship('ratedStudent', 'name')
                    ->options(function (callable $get) {
                        // given options are only students from the selected project
                        $projectId = $get('project_id');

                        if ($projectId) {
                            return User::whereHas('projects', function ($query) use($projectId) {
                                $query->where('projects.id', '=', $projectId);
                            })->pluck('name', 'id');
                        }

                        return [];
                    })
                    ->required(),
                Forms\Components\Toggle::make('self_evaluation')->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
