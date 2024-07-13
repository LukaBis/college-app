<?php

namespace App\Filament\Resources\ValuationTermResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ValuationsRelationManager extends RelationManager
{
    protected static string $relationship = 'valuations';

    public function isReadOnly(): bool
    {
        return false;
    }

    private function getMarksArray(Collection $marks): array
    {
        $marksArray = [];

        foreach ($marks->toArray() as $m) {
            $marksArray[$m['mark']] = $m['mark'];
        }

        return $marksArray;
    }

    private function getMarksDescriptions(Collection $marks): array
    {
        $marksArray = [];

        foreach ($marks->toArray() as $m) {
            $marksArray[$m['mark']] = $m['description'];
        }

        return $marksArray;
    }

    public function form(Form $form): Form
    {
        $questions = $this->getOwnerRecord()->course->questions->select('id', 'title');
        $marks = $this->getOwnerRecord()->course->marks->select('mark', 'description');
        $marksArray = $this->getMarksArray($marks);
        $marksDescriptions = $this->getMarksDescriptions($marks);

        $schemaArray = [
            /*Forms\Components\Select::make('valuation_term_id')
                ->relationship(
                    name: 'valuationTerm',
                    titleAttribute: 'title',
                )
                ->default($this->getOwnerRecord()->id)
                ->disabled()
                ->required(),
            Forms\Components\Select::make('project_id')
                ->relationship(
                    name: 'project',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn (Builder $query) => $query->whereHas('students', function (Builder $query) {
                        return $query->where('users.id', auth()->user()->id);
                    })
                )
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('rated_student_id', null))
                ->required(),
            Forms\Components\Toggle::make('self_evaluation')
                ->reactive()
                ->required(),
            Forms\Components\Select::make('rated_student_id')
                ->relationship(name: 'ratedStudent', titleAttribute: 'name')
                ->options(function (callable $get) {
                    // given options are only students from the selected project
                    $projectId = $get('project_id');

                    if ($projectId) {
                        return User::whereHas('projects', function ($query) use($projectId) {
                            $query->where('projects.id', '=', $projectId);
                        })->where('id', '!=', auth()->user()->id)->pluck('name', 'id');
                    }

                    return [];
                })
                ->disabled(fn (callable $get) => $get('self_evaluation'))
                ->required(fn (callable $get) => ! $get('self_evaluation')),*/
        ];

        foreach ($questions as $question) {
            $schemaArray[] = Forms\Components\Radio::make('valuation.'.$question['id'])
                ->options($marksArray)
                ->descriptions($marksDescriptions)
                ->required()
                ->columnSpanFull()
                ->label($question['title']);
        }

        $schemaArray[] = Forms\Components\TextInput::make('extra_comment')->columnSpanFull();

        return $form->schema($schemaArray);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (! auth()->user()->hasRole('Student')) {
                    return $query;
                }

                return $query->where('student_evaluator_id', '=', auth()->user()->id);
            })
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('evaluator.full_name'),
                Tables\Columns\TextColumn::make('ratedStudent.full_name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->disabled(fn ($record) => $record->valuation !== null)->label('Evaluate'),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
