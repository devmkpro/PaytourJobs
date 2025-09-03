<?php

namespace App\Enums;

enum EducationLevel: string
{
    case FUNDAMENTAL = 'ensino_fundamental';
    case MEDIO = 'ensino_medio';
    case SUPERIOR = 'ensino_superior';
    case POS_GRADUACAO = 'pos_graduacao';

    public function getLabel(): string
    {
        return match($this) {
            self::FUNDAMENTAL => 'Ensino Fundamental',
            self::MEDIO => 'Ensino Médio',
            self::SUPERIOR => 'Ensino Superior',
            self::POS_GRADUACAO => 'Pós-graduação',
        };
    }
}