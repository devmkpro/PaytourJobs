<?php

namespace App\Models;

use App\Enums\EducationLevel;
use Illuminate\Database\Eloquent\Model;

class Candidates extends Model
{
    protected $table = 'candidates';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'desired_position',
        'education_level',
        'observations',
        'resume_path',
        'submitter_ip',
    ];

    protected $casts = [
        'education_level' => EducationLevel::class,
    ];

    public function getEducationLevelLabelAttribute(): string
    {
        return match($this->education_level) {
            EducationLevel::FUNDAMENTAL => 'Ensino Fundamental',
            EducationLevel::MEDIO => 'Ensino Médio',
            EducationLevel::SUPERIOR => 'Ensino Superior',
            EducationLevel::POS_GRADUACAO => 'Pós-graduação',
            default => '-',
        };
    }
}
