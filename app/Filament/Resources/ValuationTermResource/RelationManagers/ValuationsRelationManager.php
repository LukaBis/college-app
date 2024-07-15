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
use Filament\Tables\Columns\IconColumn;

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
        $questions = $this->getOwnerRecord()->course->questions()->with('marks')->select('id', 'title')->get();

        foreach ($questions as $question) {
            $schemaArray[] = Forms\Components\Radio::make('valuation.'.$question['id'])
                ->options($this->getMarksArray($question->marks))
                ->descriptions($this->getMarksDescriptions($question->marks))
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
                IconColumn::make('is_filled')
                    ->boolean()
                    ->label('Done'),
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
