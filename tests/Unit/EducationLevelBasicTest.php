<?php

// Teste simples sem banco de dados
test('education level enum has correct values', function () {
    $fundamental = \App\Enums\EducationLevel::FUNDAMENTAL;
    $medio = \App\Enums\EducationLevel::MEDIO;
    $superior = \App\Enums\EducationLevel::SUPERIOR;
    $pos = \App\Enums\EducationLevel::POS_GRADUACAO;
    
    expect($fundamental->value)->toBe('ensino_fundamental');
    expect($medio->value)->toBe('ensino_medio');
    expect($superior->value)->toBe('ensino_superior');
    expect($pos->value)->toBe('pos_graduacao');
});

test('education level enum has correct labels', function () {
    expect(\App\Enums\EducationLevel::FUNDAMENTAL->getLabel())->toBe('Ensino Fundamental');
    expect(\App\Enums\EducationLevel::MEDIO->getLabel())->toBe('Ensino Médio');
    expect(\App\Enums\EducationLevel::SUPERIOR->getLabel())->toBe('Ensino Superior');
    expect(\App\Enums\EducationLevel::POS_GRADUACAO->getLabel())->toBe('Pós-graduação');
});
