<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Filament\Resources\CourseResource\RelationManagers\ProjectsRelationManager;
use App\Filament\Resources\CourseResource\Widgets\ValuationOverviewTable;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->disabled(auth()->user()->hasRole('Student')),
                Forms\Components\Select::make('admins')
                    ->label('Course Admin')
                    ->searchable()
                    ->preload()
                    ->disabled(auth()->user()->hasRole('Student'))
                    ->relationship('admins', 'name')
                    ->multiple(),
                Forms\Components\FileUpload::make('student_file')
                    ->disk('student-files')
                    ->label('Upload Student Files')
                    ->acceptedFileTypes(['text/csv', 'text/plain'])
                    ->disabled(auth()->user()->hasRole('Student')),
            ]);
    }

    public static function table(Table $table): Table
    {
        $actions = [];

        $adminActions = [
            Tables\Actions\EditAction::make()->label('More')->icon(null),
        ];

        $studentActions = [
            Tables\Actions\ViewAction::make(),
        ];

        if (auth()->user()->hasRole('Student')) {
            $actions = $studentActions;
        }

        if (auth()->user()->hasRole('Course Admin')) {
            $actions = $adminActions;
        }

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->actions($actions)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->hasRole('Super Admin')) {
            return Course::query();
        }

        if (auth()->user()->hasRole('Student')) {
            $studentCoursesIds = auth()->user()->attendingCourse()->get()->pluck('id')->toArray();

            return parent::getEloquentQuery()->whereIn('id', $studentCoursesIds);
        }

        if (auth()->user()->hasRole('Course Admin')) {
            $studentCoursesIds = auth()->user()->course()->get()->pluck('id')->toArray();

            return parent::getEloquentQuery()->whereIn('id', $studentCoursesIds);
        }

        return parent::getEloquentQuery();
    }

    public static function getRelations(): array
    {
        $user = auth()->user();

        if (isset($user)) {
            if ($user->hasRole('Student')) {
                // omit Student relation manager
                return [
                    RelationGroup::make('Valuation Setup', [
                        RelationManagers\QuestionsRelationManager::class,
                    ]),
                    ProjectsRelationManager::class,
                    RelationManagers\ValuationTermsRelationManager::class,
                ];
            }
        }

        return [
            RelationGroup::make('Valuation Setup', [
                RelationManagers\QuestionsRelationManager::class,
            ]),
            RelationManagers\StudentsRelationManager::class,
            ProjectsRelationManager::class,
            RelationManagers\ValuationTermsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
            'view' => Pages\ViewCourse::route('/{record}'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ValuationOverviewTable::class,
        ];
    }
}
