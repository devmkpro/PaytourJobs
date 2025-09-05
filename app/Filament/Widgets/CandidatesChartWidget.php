<?php

namespace App\Filament\Widgets;

use App\Models\Candidates as CandidatesModel;
use App\Enums\EducationLevel;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;

class CandidatesChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;
    protected ?string $pollingInterval = '60s';
    protected static bool $isLazy = false;    
    protected ?int $height = 300;
    public ?string $filter = 'month';
    public ?string $startDate = null;
    public ?string $endDate = null;
    public ?string $educationLevel = null;
    protected int | string | array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return 'Evolução de Candidatos no Tempo';
    }
    
    protected function getFilters(): ?array
    {
        return [
            'day' => 'Hoje',
            'week' => 'Esta semana',
            'month' => 'Este mês',
            'year' => 'Este ano',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        
        $start = match ($activeFilter) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfMonth(),
        };

        $end = match ($activeFilter) {
            'day' => now()->endOfDay(),
            'week' => now()->endOfWeek()->min(now()),
            'month' => now()->endOfMonth()->min(now()),
            'year' => now()->endOfYear()->min(now()),
            default => now(),
        };

        if ($this->startDate) {
            $start = Carbon::parse($this->startDate)->startOfDay();
        }
        
        if ($this->endDate) {
            $end = Carbon::parse($this->endDate)->endOfDay();
        }
        
        $intervalType = match ($activeFilter) {
            'day' => 'hour',
            'week' => 'day',
            'month' => 'day',
            'year' => 'month',
            default => 'day',
        };
        
        $candidatesData = $this->getCandidatesData($start, $end, $intervalType);
        
        if (empty($candidatesData)) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }
        
        $labels = array_keys($candidatesData);
        $formattedLabels = array_map(function($label) use ($intervalType) {
            return match($intervalType) {
                'hour' => Carbon::parse($label)->format('H:i'),
                'day' => Carbon::parse($label)->format('d/m'),
                'month' => Carbon::parse($label)->format('M/Y'),
                default => Carbon::parse($label)->format('d/m'),
            };
        }, $labels);
        
        $educationColors = [
            EducationLevel::FUNDAMENTAL->value => ['#FF6384', 'rgba(255, 99, 132, 0.2)'],
            EducationLevel::MEDIO->value => ['#36A2EB', 'rgba(54, 162, 235, 0.2)'],
            EducationLevel::SUPERIOR->value => ['#FFCE56', 'rgba(255, 206, 86, 0.2)'],
            EducationLevel::POS_GRADUACAO->value => ['#4BC0C0', 'rgba(75, 192, 192, 0.2)'],
            'total' => ['#9966FF', 'rgba(153, 102, 255, 0.2)'],
        ];
        
        $datasets = [];
        
        if ($this->educationLevel) {
            $educationData = [];
            foreach ($candidatesData as $period => $data) {
                $educationData[] = $data['by_education'][$this->educationLevel] ?? 0;
            }
            
            $colors = $educationColors[$this->educationLevel] ?? $educationColors['total'];
            $levelLabel = $this->getEducationLevelLabel($this->educationLevel);
            
            $datasets[] = [
                'label' => $levelLabel,
                'data' => $educationData,
                'borderColor' => $colors[0],
                'backgroundColor' => $colors[1],
                'pointBackgroundColor' => $colors[0],
                'pointBorderColor' => $colors[0],
                'pointHoverBackgroundColor' => $colors[0],
                'pointHoverBorderColor' => $colors[0],
                'fill' => true,
            ];
        } else {
            $totalData = [];
            foreach ($candidatesData as $period => $data) {
                $totalData[] = $data['total'];
            }
            
            $colors = $educationColors['total'];
            
            $datasets[] = [
                'label' => 'Total de Candidatos',
                'data' => $totalData,
                'borderColor' => $colors[0],
                'backgroundColor' => $colors[1],
                'pointBackgroundColor' => $colors[0],
                'pointBorderColor' => $colors[0],
                'pointHoverBackgroundColor' => $colors[0],
                'pointHoverBorderColor' => $colors[0],
                'fill' => true,
            ];
            
            if (in_array($activeFilter, ['month', 'year'])) {
                foreach (EducationLevel::cases() as $level) {
                    $levelData = [];
                    $hasData = false;
                    
                    foreach ($candidatesData as $period => $data) {
                        $count = $data['by_education'][$level->value] ?? 0;
                        $levelData[] = $count;
                        if ($count > 0) $hasData = true;
                    }
                    
                    if ($hasData) {
                        $colors = $educationColors[$level->value];
                        $datasets[] = [
                            'label' => $this->getEducationLevelLabel($level->value),
                            'data' => $levelData,
                            'borderColor' => $colors[0],
                            'backgroundColor' => $colors[1],
                            'pointBackgroundColor' => $colors[0],
                            'pointBorderColor' => $colors[0],
                            'pointHoverBackgroundColor' => $colors[0],
                            'pointHoverBorderColor' => $colors[0],
                            'fill' => false,
                        ];
                    }
                }
            }
        }
        
        return [
            'datasets' => $datasets,
            'labels' => $formattedLabels,
        ];
    }

    private function getCandidatesData(Carbon $start, Carbon $end, string $intervalType): array
    {
        $data = [];
        $current = $start->copy();
        
        while ($current <= $end) {
            $periodStart = $current->copy();
            $periodEnd = match($intervalType) {
                'hour' => $current->copy()->endOfHour(),
                'day' => $current->copy()->endOfDay(),
                'month' => $current->copy()->endOfMonth(),
                default => $current->copy()->endOfDay(),
            };
            
            $periodEnd = $periodEnd->min($end);
            
            $periodKey = match($intervalType) {
                'hour' => $periodStart->format('Y-m-d H:i'),
                'day' => $periodStart->format('Y-m-d'),
                'month' => $periodStart->format('Y-m'),
                default => $periodStart->format('Y-m-d'),
            };
            
            $totalCandidates = CandidatesModel::whereBetween('created_at', [$periodStart, $periodEnd])->count();
            
            $byEducation = [];
            foreach (EducationLevel::cases() as $level) {
                $byEducation[$level->value] = CandidatesModel::whereBetween('created_at', [$periodStart, $periodEnd])
                    ->where('education_level', $level->value)
                    ->count();
            }
            
            $data[$periodKey] = [
                'total' => $totalCandidates,
                'by_education' => $byEducation,
            ];
            
            match($intervalType) {
                'hour' => $current->addHour(),
                'day' => $current->addDay(),
                'month' => $current->addMonth(),
                default => $current->addDay(),
            };
        }
        
        return $data;
    }

    private function getEducationLevelLabel(string $level): string
    {
        return match($level) {
            EducationLevel::FUNDAMENTAL->value => 'Ensino Fundamental',
            EducationLevel::MEDIO->value => 'Ensino Médio',
            EducationLevel::SUPERIOR->value => 'Ensino Superior',
            EducationLevel::POS_GRADUACAO->value => 'Pós-graduação',
            default => 'Não informado',
        };
    }

    protected function getType(): string
    {
        return 'line';
    }
    
    #[On('refresh-candidates-chart')]
    public function updateStats($event): void
    {
        $this->startDate = $event['startDate'] ?? null;
        $this->endDate = $event['endDate'] ?? null;
        $this->educationLevel = !empty($event['educationLevel']) ? $event['educationLevel'] : null;
        $this->filter = $event['period'] ?? $this->filter;
    }
}
