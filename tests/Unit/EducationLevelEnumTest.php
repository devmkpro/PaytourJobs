<?php

use App\Enums\EducationLevel;

describe('EducationLevel Enum', function () {
    
    it('has all expected cases', function () {
        $cases = EducationLevel::cases();
        
        expect($cases)->toHaveCount(4)
            ->and($cases[0])->toBe(EducationLevel::FUNDAMENTAL)
            ->and($cases[1])->toBe(EducationLevel::MEDIO)
            ->and($cases[2])->toBe(EducationLevel::SUPERIOR)
            ->and($cases[3])->toBe(EducationLevel::POS_GRADUACAO);
    });

    it('has correct values for each case', function () {
        expect(EducationLevel::FUNDAMENTAL->value)->toBe('ensino_fundamental')
            ->and(EducationLevel::MEDIO->value)->toBe('ensino_medio')
            ->and(EducationLevel::SUPERIOR->value)->toBe('ensino_superior')
            ->and(EducationLevel::POS_GRADUACAO->value)->toBe('pos_graduacao');
    });

    it('returns correct labels', function () {
        expect(EducationLevel::FUNDAMENTAL->getLabel())->toBe('Ensino Fundamental')
            ->and(EducationLevel::MEDIO->getLabel())->toBe('Ensino Médio')
            ->and(EducationLevel::SUPERIOR->getLabel())->toBe('Ensino Superior')
            ->and(EducationLevel::POS_GRADUACAO->getLabel())->toBe('Pós-graduação');
    });

    it('can be instantiated from string values', function () {
        expect(EducationLevel::from('ensino_fundamental'))->toBe(EducationLevel::FUNDAMENTAL)
            ->and(EducationLevel::from('ensino_medio'))->toBe(EducationLevel::MEDIO)
            ->and(EducationLevel::from('ensino_superior'))->toBe(EducationLevel::SUPERIOR)
            ->and(EducationLevel::from('pos_graduacao'))->toBe(EducationLevel::POS_GRADUACAO);
    });

    it('throws exception for invalid values', function () {
        expect(fn() => EducationLevel::from('invalid_value'))
            ->toThrow(ValueError::class);
    });

    it('can use tryFrom safely', function () {
        expect(EducationLevel::tryFrom('ensino_superior'))->toBe(EducationLevel::SUPERIOR)
            ->and(EducationLevel::tryFrom('invalid_value'))->toBeNull();
    });

    it('can be serialized to json', function () {
        $level = EducationLevel::SUPERIOR;
        
        expect(json_encode($level))->toBe('"ensino_superior"');
    });

    it('maintains consistency between value and string representation', function () {
        foreach (EducationLevel::cases() as $case) {
            expect($case->value)->toBeString()
                ->and(strlen($case->value))->toBeGreaterThan(0)
                ->and($case->getLabel())->toBeString()
                ->and(strlen($case->getLabel()))->toBeGreaterThan(0);
        }
    });
});
