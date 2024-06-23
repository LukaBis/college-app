<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

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
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('password')->password()->required(),
                Forms\Components\TextInput::make('jmbag'),
                Forms\Components\Toggle::make('active')->required(),
                Forms\Components\Select::make('roles')
                    ->preload()
                    ->relationship('roles', 'name')
                    ->default(Role::where('name', 'Student')->first()->id)
                    ->disabled(auth()->user()->hasRole('Student')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('surname')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\BooleanColumn::make('active'),
            ])
            ->filters([
                Filter::make('active')
                    ->query(fn (Builder $query): Builder => $query->where('active', true)),
                Filter::make('non-active')
                    ->query(fn (Builder $query): Builder => $query->where('active', false)),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $course = $this->getOwnerRecord();
                        if ($course->students()->count() >= $course->max_students) {
                            Notification::make()
                                ->title('Error')
                                ->body('The maximum number of students for this course has been reached.')
                                ->danger()
                                ->send();
                            abort(403, 'The maximum number of students for this course has been reached.');
                        }

                        return $data;
                    }),
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
