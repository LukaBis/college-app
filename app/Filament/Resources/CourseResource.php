<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Filament\Resources\CourseResource\RelationManagers\ProjectsRelationManager;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                Forms\Components\Select::make('course_admin_id')
                    ->label('Course Admin')
                    ->relationship('courseAdmin', 'name')
                    ->searchable()
                    ->preload()
                    ->disabled(auth()->user()->hasRole('Student')),
                Forms\Components\FileUpload::make('student_file')
                    ->disk('student-files')
                    ->label('Upload Student Files')
                    ->hint('Refresh the page after file is uploaded!')
                    ->acceptedFileTypes(['text/csv', 'text/plain'])
                    ->disabled(auth()->user()->hasRole('Student')),
            ]);
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
                Tables\Actions\EditAction::make()->label('More')->icon(null),
            ])
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

        // Ensure that only the courses of the authenticated user are fetched
        return parent::getEloquentQuery()->where('course_admin_id', auth()->id());
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\StudentsRelationManager::class,
            ProjectsRelationManager::class,
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
}
