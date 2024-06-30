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

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('valuation_term_id')
                    ->relationship('valuationTerm', 'title')
                    ->default($this->getOwnerRecord()->id)
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
                Forms\Components\Select::make('mark1')
                    ->options(['A', 'B', 'C', 'D'])
                    ->required()
                    ->columnSpanFull()
                    ->label('Opci doprinos'),
                Forms\Components\Select::make('mark2')
                    ->options(['A', 'B', 'C', 'D'])
                    ->required()
                    ->columnSpanFull()
                    ->label('Rješavanje problema'),
                Forms\Components\Select::make('mark3')
                    ->options(['A', 'B', 'C', 'D'])
                    ->required()
                    ->columnSpanFull()
                    ->label('Stav'),
                Forms\Components\Select::make('mark4')
                    ->options(['A', 'B', 'C', 'D'])
                    ->required()
                    ->columnSpanFull()
                    ->label('Usredotočenost na zadatak'),
                Forms\Components\Select::make('mark5')
                    ->options(['A', 'B', 'C', 'D'])
                    ->required()
                    ->columnSpanFull()
                    ->label('Suradnja s ostalim članovima'),
                Forms\Components\Select::make('mark6')
                    ->options(['A', 'B', 'C', 'D'])
                    ->required()
                    ->columnSpanFull()
                    ->label('Sastanci'),
                Forms\Components\Select::make('mark7')
                    ->options(['A', 'B', 'C', 'D'])
                    ->required()
                    ->columnSpanFull()
                    ->label('Prihvaćanje zadataka i poštivanje rokova'),
                Forms\Components\TextInput::make('extra_comment')->columnSpanFull(),
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
