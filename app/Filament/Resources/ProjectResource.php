<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers\ActivitiesRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\MeetingsRelationManager;
use App\Filament\Resources\ProjectResource\RelationManagers\StudentsRelationManager;
use App\Filament\Resources\ProjectResource\Widgets\AssignToProjectWidget;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('About')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->disabled(auth()->user()->hasRole('Student')),
                                Forms\Components\TextInput::make('description')
                                    ->required()
                                    ->disabled(auth()->user()->hasRole('Student')),
                                Forms\Components\TextInput::make('max_students')
                                    ->required()
                                    ->numeric()
                                    ->label('Max Number of Students')
                                    ->disabled(auth()->user()->hasRole('Student')),
                                Forms\Components\TextInput::make('max_points')
                                    ->required()
                                    ->numeric()
                                    ->label('Max Possible Points')
                                    ->disabled(auth()->user()->hasRole('Student')),
                                Forms\Components\TextInput::make('given_points')
                                    ->required()
                                    ->numeric()
                                    ->label('Given Points')
                                    ->disabled(auth()->user()->hasRole('Student')),
                            ]),
                        Tabs\Tab::make('Documentation')
                            ->schema([
                                Forms\Components\FileUpload::make('team_declaration')
                                    ->disk('docs')
                                    ->label('Upload Team Declaration')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->openable(),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            AssignToProjectWidget::class,
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            StudentsRelationManager::class,
            MeetingsRelationManager::class,
            ActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
            'view' => Pages\ViewProject::route('/{record}'),
        ];
    }
}
