<?php

namespace App\Livewire;

use App\Enums\EducationLevel;
use App\Models\Candidates;
use App\Traits\GetsRealIpAddress;
use Livewire\Component;
use Livewire\WithFileUploads;

class CandidateApplication extends Component
{
    use WithFileUploads, GetsRealIpAddress;

    public $name = '';
    public $email = '';
    public $phone = '';
    public $desired_position = '';
    public $education_level = '';
    public $observations = '';
    public $resume_path;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|min:10|max:15',
        'desired_position' => 'required|string|max:255',
        'education_level' => 'required|string',
        'observations' => 'nullable|string|max:1000',
        'resume_path' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB max
    ];

    protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'email.required' => 'O email é obrigatório.',
        'email.email' => 'Digite um email válido.',
        'email.unique' => 'Este email já foi cadastrado.',
        'phone.required' => 'O telefone é obrigatório.',
        'phone.min' => 'Digite um telefone válido com DDD.',
        'phone.max' => 'Telefone muito longo.',
        'desired_position.required' => 'O cargo desejado é obrigatório.',
        'education_level.required' => 'O nível de escolaridade é obrigatório.',
        'resume_path.mimes' => 'O currículo deve ser um arquivo PDF, DOC ou DOCX.',
        'resume_path.max' => 'O arquivo deve ter no máximo 5MB.',
    ];

    public function updatedPhone($value)
    {
        $cleanPhone = preg_replace('/\D/', '', $value);
        if (strlen($cleanPhone) >= 10) {
            $this->validateOnly('phone');
        }
    }

    public function updatedEmail()
    {
        $this->validateOnly('email');
    }

    public function getEducationLevelsProperty()
    {
        return [
            EducationLevel::FUNDAMENTAL->value => 'Ensino Fundamental',
            EducationLevel::MEDIO->value => 'Ensino Médio',
            EducationLevel::SUPERIOR->value => 'Ensino Superior',
            EducationLevel::POS_GRADUACAO->value => 'Pós-graduação',
        ];
    }

    public function submit()
    {
        $cleanPhone = preg_replace('/\D/', '', $this->phone);
        if (strlen($cleanPhone) < 10 || strlen($cleanPhone) > 11) {
            $this->addError('phone', 'Digite um telefone válido com DDD.');
            return;
        }
        
        $this->validate();
        
        $existingByEmail = Candidates::where('email', $this->email)->exists();
        if ($existingByEmail) {
            session()->flash('error', 'Este email já foi utilizado para uma candidatura.');
            return;
        }

        $clientIp = $this->getRealIpAddr();
        $existingByIp = Candidates::where('submitter_ip', $clientIp)
            ->where('created_at', '>=', now()->subDay())
            ->exists();
        
        if ($existingByIp) {
            session()->flash('error', 'Já foi registrada uma candidatura deste local nas últimas 24 horas.');
            return;
        }
        
        $resumePath = null;
        if ($this->resume_path) {
            $resumePath = $this->resume_path->store('resumes', 'public');
        }
        
        Candidates::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'desired_position' => $this->desired_position,
            'education_level' => $this->education_level,
            'observations' => $this->observations,
            'resume_path' => $resumePath,
            'submitter_ip' => $clientIp,
        ]);
        
        session()->flash('success', 'Candidatura enviada com sucesso! Entraremos em contato em breve.');
        
        $this->reset(['name', 'email', 'phone', 'desired_position', 'education_level', 'observations', 'resume_path']);
    }

    public function render()
    {
        return view('livewire.candidate-application');
    }
}
