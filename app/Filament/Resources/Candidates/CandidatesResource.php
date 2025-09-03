<?php

namespace App\Filament\Resources\Candidates;

use App\Enums\EducationLevel;
use App\Filament\Exports\CandidatesExporter;
use App\Filament\Resources\Candidates\Pages\ManageCandidates;
use App\Models\Candidates;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ExportAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CandidatesResource extends Resource
{
    protected static ?string $model = Candidates::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    public static function getNavigationLabel(): string
    {
        return __('Candidates');
    }

    public static function getModelLabel(): string
    {
        return __('Candidates');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Personal Information'))
                    ->description(__('Basic candidate information'))
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('Full Name'))
                                    ->placeholder(__('Enter the candidate\'s full name'))
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label(__('Email Address'))
                                    ->placeholder(__('candidate@example.com'))
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                            ]),
                        Grid::make(1)
                            ->schema([
                                TextInput::make('phone')
                                    ->label(__('Phone Number'))
                                    ->placeholder(__('(11) 99999-9999'))
                                    ->tel()
                                    ->mask('(99) 99999-9999')
                                    ->required()
                                    ->maxLength(20),
                                TextInput::make('desired_position')
                                    ->label(__('Desired Position'))
                                    ->placeholder(__('Enter the desired job position'))
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make(__('Education & Documents'))
                    ->description(__('Education level and resume information'))
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Select::make('education_level')
                                    ->label(__('Education Level'))
                                    ->placeholder(__('Select education level'))
                                    ->options(EducationLevel::class)
                                    ->searchable()
                                    ->required(),
                                FileUpload::make('resume_path')
                                    ->label(__('Resume/CV'))
                                    ->placeholder(__('Upload resume file'))
                                    ->acceptedFileTypes([
                                        'application/pdf',
                                        'application/msword',
                                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                                    ])
                                    ->maxSize(10240) // 10MB
                                    ->disk('public')
                                    ->directory('resumes')
                                    ->visibility('public')
                                    ->downloadable()
                                    ->openable()
                                    ->previewable()
                                    ->helperText(__('Upload PDF, DOC or DOCX files. Maximum size: 10MB'))
                                    ->required(),
                            ]),
                    ]),

                Section::make(__('Additional Information'))
                    ->description(__('Optional observations and system data'))
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('observations')
                            ->label(__('Observations'))
                            ->placeholder(__('Any additional notes about the candidate'))
                            ->rows(4)
                            ->columnSpanFull(),
                        Grid::make(1)
                            ->schema([
                                TextInput::make('submitter_ip')
                                    ->label(__('Submitter IP'))
                                    ->placeholder(__('IP address will be automatically filled'))
                                    ->disabled()
                                    ->dehydrated()
                                    ->default(request()->ip()),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('Email address'))
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label(__('Phone number'))
                    ->searchable()
                    ->copyable(),
                TextColumn::make('desired_position')
                    ->label(__('Desired position'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('education_level')
                    ->label(__('Education level'))
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn(Candidates $record) => $record->education_level_label)
                    ->badge(),
                TextColumn::make('resume_path')
                    ->label(__('Resume'))
                    ->formatStateUsing(fn($state) => $state ? __('Uploaded') : __('Not uploaded'))
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger'),
                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('download_resume')
                        ->label(__('Download Resume'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('info')
                        ->url(fn(Candidates $record) => $record->resume_path && Storage::disk('public')->exists($record->resume_path)
                            ? Storage::url($record->resume_path)
                            : null)
                        ->openUrlInNewTab()
                        ->visible(
                            fn(Candidates $record) =>
                            Auth::check() &&
                                Auth::user()?->can('download_resumes') &&
                                !empty($record->resume_path) &&
                                Storage::disk('public')->exists($record->resume_path)
                        ),
                    EditAction::make(),
                    DeleteAction::make(),
                ])

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportAction::make()
                        ->exporter(CandidatesExporter::class)
                        ->label(__('Export Candidates'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->visible(fn() => Auth::check() && Auth::user()?->can('export_candidates')),
                    DeleteBulkAction::make(),

                ]),
            ])
            ->poll('15s');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCandidates::route('/'),
        ];
    }
}
