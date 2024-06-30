<?php

namespace App\Filament\Resources\ValuationTermResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
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
                    ->required(fn (callable $get) => ! $get('self_evaluation')),
                Forms\Components\Radio::make('mark1')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D'
                    ])
                    ->descriptions([
                        'A' => 'Rutinski daje korisne ideje kada sudjeluje u raspravi. Vođa koji ulaže puno truda.',
                        'B' => 'Obično daje korisne ideje kada sudjeluje u raspravi. Snažan član tima koji se jako trudi.',
                        'C' => 'Ponekad daje korisne ideje kada sudjeluje u raspravi. Prolazno zadovoljavajući član tima koji radi ono što mora.',
                        'D' => 'Rijetko daje korisne ideje kada sudjeluje u raspravi ili zna odbiti sudjelovanje. '
                    ])
                    ->required()
                    ->columnSpanFull()
                    ->label('Opci doprinos'),
                Forms\Components\Radio::make('mark2')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D'
                    ])
                    ->descriptions([
                        'A' => 'Rutinski daje korisne ideje kada sudjeluje u raspravi. Vođa koji ulaže puno truda.',
                        'B' => 'Obično daje korisne ideje kada sudjeluje u raspravi. Snažan član tima koji se jako trudi.',
                        'C' => 'Ponekad daje korisne ideje kada sudjeluje u raspravi. Prolazno zadovoljavajući član tima koji radi ono što mora.',
                        'D' => 'Rijetko daje korisne ideje kada sudjeluje u raspravi ili zna odbiti sudjelovanje. '
                    ])
                    ->required()
                    ->columnSpanFull()
                    ->label('Rješavanje problema'),
                Forms\Components\Radio::make('mark3')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D'
                    ])
                    ->descriptions([
                        'A' => 'Rutinski daje korisne ideje kada sudjeluje u raspravi. Vođa koji ulaže puno truda.',
                        'B' => 'Obično daje korisne ideje kada sudjeluje u raspravi. Snažan član tima koji se jako trudi.',
                        'C' => 'Ponekad daje korisne ideje kada sudjeluje u raspravi. Prolazno zadovoljavajući član tima koji radi ono što mora.',
                        'D' => 'Rijetko daje korisne ideje kada sudjeluje u raspravi ili zna odbiti sudjelovanje. '
                    ])
                    ->required()
                    ->columnSpanFull()
                    ->label('Stav'),
                Forms\Components\Radio::make('mark4')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D'
                    ])
                    ->descriptions([
                        'A' => 'Rutinski daje korisne ideje kada sudjeluje u raspravi. Vođa koji ulaže puno truda.',
                        'B' => 'Obično daje korisne ideje kada sudjeluje u raspravi. Snažan član tima koji se jako trudi.',
                        'C' => 'Ponekad daje korisne ideje kada sudjeluje u raspravi. Prolazno zadovoljavajući član tima koji radi ono što mora.',
                        'D' => 'Rijetko daje korisne ideje kada sudjeluje u raspravi ili zna odbiti sudjelovanje. '
                    ])
                    ->required()
                    ->columnSpanFull()
                    ->label('Usredotočenost na zadatak'),
                Forms\Components\Radio::make('mark5')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D'
                    ])
                    ->descriptions([
                        'A' => 'Rutinski daje korisne ideje kada sudjeluje u raspravi. Vođa koji ulaže puno truda.',
                        'B' => 'Obično daje korisne ideje kada sudjeluje u raspravi. Snažan član tima koji se jako trudi.',
                        'C' => 'Ponekad daje korisne ideje kada sudjeluje u raspravi. Prolazno zadovoljavajući član tima koji radi ono što mora.',
                        'D' => 'Rijetko daje korisne ideje kada sudjeluje u raspravi ili zna odbiti sudjelovanje. '
                    ])
                    ->required()
                    ->columnSpanFull()
                    ->label('Suradnja s ostalim članovima'),
                Forms\Components\Radio::make('mark6')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D'
                    ])
                    ->descriptions([
                        'A' => 'Rutinski daje korisne ideje kada sudjeluje u raspravi. Vođa koji ulaže puno truda.',
                        'B' => 'Obično daje korisne ideje kada sudjeluje u raspravi. Snažan član tima koji se jako trudi.',
                        'C' => 'Ponekad daje korisne ideje kada sudjeluje u raspravi. Prolazno zadovoljavajući član tima koji radi ono što mora.',
                        'D' => 'Rijetko daje korisne ideje kada sudjeluje u raspravi ili zna odbiti sudjelovanje. '
                    ])
                    ->required()
                    ->columnSpanFull()
                    ->label('Sastanci'),
                Forms\Components\Radio::make('mark7')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D'
                    ])
                    ->descriptions([
                        'A' => 'Rutinski daje korisne ideje kada sudjeluje u raspravi. Vođa koji ulaže puno truda.',
                        'B' => 'Obično daje korisne ideje kada sudjeluje u raspravi. Snažan član tima koji se jako trudi.',
                        'C' => 'Ponekad daje korisne ideje kada sudjeluje u raspravi. Prolazno zadovoljavajući član tima koji radi ono što mora.',
                        'D' => 'Rijetko daje korisne ideje kada sudjeluje u raspravi ili zna odbiti sudjelovanje. '
                    ])
                    ->required()
                    ->columnSpanFull()
                    ->label('Prihvaćanje zadataka i poštivanje rokova'),
                Forms\Components\TextInput::make('extra_comment')->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('student_evaluator_id', '=', auth()->user()->id))
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
