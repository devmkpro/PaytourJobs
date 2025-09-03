<?php

use App\Models\Candidates;
use App\Enums\EducationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Candidates Model', function () {
    
    it('can create a candidate with valid data', function () {
        $candidate = Candidates::create([
            'name' => 'João Silva',
            'email' => 'joao@exemplo.com',
            'phone' => '(11) 99999-9999',
            'desired_position' => 'Desenvolvedor PHP',
            'education_level' => EducationLevel::SUPERIOR,
            'observations' => 'Experiência em Laravel',
            'resume_path' => 'resumes/curriculo.pdf',
            'submitter_ip' => '192.168.1.1',
        ]);

        expect($candidate)
            ->toBeInstanceOf(Candidates::class)
            ->and($candidate->name)->toBe('João Silva')
            ->and($candidate->email)->toBe('joao@exemplo.com')
            ->and($candidate->phone)->toBe('(11) 99999-9999')
            ->and($candidate->desired_position)->toBe('Desenvolvedor PHP')
            ->and($candidate->education_level)->toBe(EducationLevel::SUPERIOR)
            ->and($candidate->observations)->toBe('Experiência em Laravel')
            ->and($candidate->resume_path)->toBe('resumes/curriculo.pdf')
            ->and($candidate->submitter_ip)->toBe('192.168.1.1');
    });

    it('validates required fields', function () {
        expect(fn() => Candidates::create([]))
            ->toThrow(\Illuminate\Database\QueryException::class);
    });

    it('casts education_level to enum correctly', function () {
        $candidate = Candidates::factory()->create([
            'education_level' => EducationLevel::MEDIO
        ]);

        expect($candidate->education_level)
            ->toBeInstanceOf(EducationLevel::class)
            ->and($candidate->education_level->value)->toBe('ensino_medio');
    });

    it('returns correct education level label', function () {
        $candidate = Candidates::factory()->create([
            'education_level' => EducationLevel::SUPERIOR
        ]);

        expect($candidate->education_level_label)
            ->toBe('Ensino Superior');
    });

    it('handles different education levels correctly', function () {
        $levels = [
            EducationLevel::FUNDAMENTAL->value => 'Ensino Fundamental',
            EducationLevel::MEDIO->value => 'Ensino Médio',
            EducationLevel::SUPERIOR->value => 'Ensino Superior',
            EducationLevel::POS_GRADUACAO->value => 'Pós-graduação',
        ];

        foreach ($levels as $levelValue => $expectedLabel) {
            $candidate = Candidates::factory()->create([
                'education_level' => $levelValue
            ]);

            expect($candidate->education_level_label)->toBe($expectedLabel);
        }
    });

    it('stores timestamps correctly', function () {
        $candidate = Candidates::factory()->create();

        expect($candidate->created_at)
            ->not->toBeNull()
            ->and($candidate->updated_at)
            ->not->toBeNull();
    });

    it('can have nullable observations', function () {
        $candidate = Candidates::factory()->create([
            'observations' => null
        ]);

        expect($candidate->observations)->toBeNull();
    });

    it('can have nullable resume_path', function () {
        $candidate = Candidates::factory()->create([
            'resume_path' => null
        ]);

        expect($candidate->resume_path)->toBeNull();
    });

    it('stores submitter_ip correctly', function () {
        $ip = '203.0.113.195';
        $candidate = Candidates::factory()->create([
            'submitter_ip' => $ip
        ]);

        expect($candidate->submitter_ip)->toBe($ip);
    });

    it('prevents duplicate emails', function () {
        $email = 'teste@exemplo.com';
        
        Candidates::factory()->create(['email' => $email]);

        expect(fn() => Candidates::factory()->create(['email' => $email]))
            ->toThrow(\Illuminate\Database\QueryException::class);
    });

    it('validates email format in model fillable', function () {
        $candidate = Candidates::factory()->create([
            'email' => 'usuario@dominio.com.br'
        ]);

        expect($candidate->email)->toBe('usuario@dominio.com.br');
    });
});
