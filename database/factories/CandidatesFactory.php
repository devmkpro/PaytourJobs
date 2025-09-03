<?php

namespace Database\Factories;

use App\Enums\EducationLevel;
use App\Models\Candidates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidates>
 */
class CandidatesFactory extends Factory
{
    protected $model = Candidates::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->randomElement([
                '(11) 99999-9999',
                '(21) 98888-8888',
                '(31) 97777-7777',
                '(41) 96666-6666',
                '(51) 95555-5555'
            ]),
            'desired_position' => $this->faker->randomElement([
                'Desenvolvedor PHP',
                'Desenvolvedor JavaScript',
                'Analista de Sistemas',
                'Designer UX/UI',
                'Gerente de Projetos',
                'Analista de Marketing',
                'Contador',
                'Assistente Administrativo'
            ]),
            'education_level' => $this->faker->randomElement(EducationLevel::cases()),
            'observations' => $this->faker->optional()->paragraph(),
            'resume_path' => $this->faker->optional()->filePath(),
            'submitter_ip' => $this->faker->ipv4(),
        ];
    }

    /**
     * State for candidate with resume
     */
    public function withResume(): static
    {
        return $this->state(fn (array $attributes) => [
            'resume_path' => 'resumes/' . $this->faker->uuid() . '.pdf',
        ]);
    }

    /**
     * State for candidate without resume
     */
    public function withoutResume(): static
    {
        return $this->state(fn (array $attributes) => [
            'resume_path' => null,
        ]);
    }

    /**
     * State for specific education level
     */
    public function withEducationLevel(EducationLevel $level): static
    {
        return $this->state(fn (array $attributes) => [
            'education_level' => $level,
        ]);
    }

    /**
     * State for specific IP
     */
    public function withIp(string $ip): static
    {
        return $this->state(fn (array $attributes) => [
            'submitter_ip' => $ip,
        ]);
    }
}
