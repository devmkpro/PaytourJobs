<?php

namespace App\Filament\Pages;

use App\Enums\EducationLevel;
use App\Models\Candidates;
use App\Traits\GetsRealIpAddress;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\SimplePage;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form as FormComponent;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class CandidateApplication extends SimplePage
{
    use GetsRealIpAddress;
    
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $title = 'Candidatar-se';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function getTitle(): string | Htmlable
    {
        return __('Job Application');
    }

    public function getHeading(): string | Htmlable
    {
        return __('Submit Your Application');
    }

    public function getView(): string
    {
        return 'filament.pages.candidate-application';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Personal Information'))
                    ->description(__('Please provide your basic information'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('Full Name'))
                                    ->placeholder(__('Enter your full name'))
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label(__('Email Address'))
                                    ->placeholder(__('your.email@example.com'))
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Grid::make(2)
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
                                    ->placeholder(__('What position are you applying for?'))
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make(__('Education & Resume'))
                    ->description(__('Your education level and resume'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('education_level')
                                    ->label(__('Education Level'))
                                    ->placeholder(__('Select your education level'))
                                    ->options([
                                        EducationLevel::FUNDAMENTAL->value => 'Ensino Fundamental',
                                        EducationLevel::MEDIO->value => 'Ensino Médio',
                                        EducationLevel::SUPERIOR->value => 'Ensino Superior',
                                        EducationLevel::POS_GRADUACAO->value => 'Pós-graduação',
                                    ])
                                    ->searchable()
                                    ->required(),
                                FileUpload::make('resume_path')
                                    ->label(__('Resume/CV'))
                                    ->placeholder(__('Upload your resume'))
                                    ->acceptedFileTypes([
                                        'application/pdf',
                                        'application/msword',
                                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                                    ])
                                    ->maxSize(10240) // 10MB
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
                    ->description(__('Any additional information you\'d like to share'))
                    ->schema([
                        Textarea::make('observations')
                            ->label(__('Additional Notes'))
                            ->placeholder(__('Tell us more about yourself, your experience, or anything else you\'d like us to know'))
                            ->rows(4),
                    ])
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        
        // Add the submitter IP
        $data['submitter_ip'] = $this->getRealIpAddr();
        
        try {
            Candidates::create($data);
            
            Notification::make()
                ->title(__('Application Submitted Successfully!'))
                ->body(__('Thank you for your application. We will review it and contact you soon.'))
                ->success()
                ->send();
                
            // Reset form
            $this->form->fill();
            
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('Error'))
                ->body(__('There was an error submitting your application. Please try again.'))
                ->danger()
                ->send();
        }
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        FormComponent::make([EmbeddedSchema::make('form')])
                            ->id('form')
                            ->livewireSubmitHandler('submit')
                            ->footer([
                                Actions::make([
                                    Action::make('submit')
                                        ->label(__('Submit Application'))
                                        ->submit('submit')
                                        ->color('primary')
                                        ->size('lg'),
                                ])
                                ->alignment('center')
                                ->fullWidth(),
                            ])
                    ])
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false; // Hide from navigation
    }

    public static function getRouteName(): string
    {
        return 'candidate-application';
    }

    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?Model $tenant = null): string
    {
        return route('candidate-application');
    }
}
