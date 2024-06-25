<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    protected static ?string $inverseRelationship = 'attendingCourse';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('surname')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Forms\Components\TextInput::make('jmbag')->disabled(auth()->user()->hasRole('Student')),
                Forms\Components\Toggle::make('active')->required()->disabled(auth()->user()->hasRole('Student')),
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
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordTitle(fn (User $record): string => "{$record->name} {$record->surname} ({$record->email})")
                    ->disabled(auth()->user()->hasRole('Student')),
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
