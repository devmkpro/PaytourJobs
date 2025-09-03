<?php

namespace App\Filament\Exports;

use App\Models\Candidates;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class CandidatesExporter extends Exporter
{
    protected static ?string $model = Candidates::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label('Nome'),
            ExportColumn::make('email')
                ->label('Email'),
            ExportColumn::make('phone')
                ->label('Telefone'),
            ExportColumn::make('desired_position')
                ->label('Cargo Desejado'),
            ExportColumn::make('education_level')
                ->label('Nível de Escolaridade')
                ->formatStateUsing(function($state, $record) {
                    if ($record && $record->education_level instanceof \App\Enums\EducationLevel) {
                        return $record->education_level->getLabel();
                    }
                    if (is_string($state)) {
                        $enum = \App\Enums\EducationLevel::tryFrom($state);
                        return $enum ? $enum->getLabel() : $state;
                    }
                    return $state instanceof \App\Enums\EducationLevel ? $state->getLabel() : (string) $state;
                }),
            ExportColumn::make('observations')
                ->label('Observações'),
            ExportColumn::make('resume_path')
                ->label('Currículo')
                ->formatStateUsing(fn($state) => $state ? 'Enviado' : 'Não enviado'),
            ExportColumn::make('submitter_ip')
                ->label('IP do Candidato'),
            ExportColumn::make('created_at')
                ->label('Data de Criação')
                ->formatStateUsing(fn($state) => $state ? $state->format('d/m/Y H:i') : ''),
            ExportColumn::make('updated_at')
                ->label('Última Atualização')
                ->formatStateUsing(fn($state) => $state ? $state->format('d/m/Y H:i') : ''),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Exportação de candidatos concluída! ' . Number::format($export->successful_rows) . ' ' . str('registro')->plural($export->successful_rows) . ' exportado' . ($export->successful_rows > 1 ? 's' : '') . '.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('registro')->plural($failedRowsCount) . ' falharam na exportação.';
        }

        return $body;
    }
}
