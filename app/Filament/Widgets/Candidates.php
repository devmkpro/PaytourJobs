<?php

namespace App\Filament\Widgets;

use App\Models\Candidates as CandidatesModel;
use App\Enums\EducationLevel;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\On;

class Candidates extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '60s';
    protected static ?int $sort = 1;
    protected static bool $isLazy = false;

    public ?string $filter = 'month';
    public ?string $educationFilter = null;

    public function getHeading(): ?string
    {
        return 'Estatísticas Gerais de Candidatos';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hoje',
            'week' => 'Esta semana',
            'month' => 'Este mês',
            'quarter' => 'Este trimestre',
            'year' => 'Este ano',
            'all' => 'Todos os tempos',
        ];
    }

    public function updatedFilter(): void
    {
        $this->dispatch('refresh-candidates-chart', [
            'period' => $this->filter,
            'educationLevel' => $this->educationFilter,
        ]);
    }

    protected function getStats(): array
    {
        $activeFilter = $this->filter;

        [$start, $end] = $this->getPeriodFromFilter($activeFilter);
        
        $query = CandidatesModel::whereBetween('created_at', [$start, $end]);
        
        if ($this->educationFilter) {
            $query->where('education_level', $this->educationFilter);
        }
        
        $totalCandidates = $query->count();
        
        $recentStart = max($start, Carbon::now()->subWeek());
        $recentCandidates = CandidatesModel::whereBetween('created_at', [$recentStart, $end])
            ->when($this->educationFilter, fn($q) => $q->where('education_level', $this->educationFilter))
            ->count();
        
        $trend = $this->calculateTrend($totalCandidates, $start, $end);
        
        $daysInPeriod = max(1, $start->diffInDays($end) + 1);
        $dailyAverage = round($totalCandidates / $daysInPeriod, 1);
        
        $withResume = CandidatesModel::whereBetween('created_at', [$start, $end])
            ->when($this->educationFilter, fn($q) => $q->where('education_level', $this->educationFilter))
            ->whereNotNull('resume_path')
            ->count();

        $resumePercentage = $totalCandidates > 0 ? round(($withResume / $totalCandidates) * 100, 1) : 0;
        
        $educationFilterName = $this->educationFilter ? 
            ' - ' . $this->getEducationLevelLabel($this->educationFilter) : '';
        
        $chartData = $this->getChartData($start, $end);
        $chartData = $this->getChartData($start, $end);
        
        return [
            Stat::make('Total de Candidatos' . $educationFilterName, $totalCandidates)
                ->description($trend['text'])
                ->descriptionIcon($trend['icon'])
                ->chart($chartData)
                ->color($trend['color']),
                
            Stat::make('Últimos 7 dias', $recentCandidates)
                ->description('Candidatos recentes')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
                
            Stat::make('Média Diária', $dailyAverage)
                ->description('Candidatos por dia')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('warning'),
                
            Stat::make('Com Currículo', $withResume)
                ->description($resumePercentage . '% enviaram CV')
                ->descriptionIcon('heroicon-m-document')
                ->color('success'),
        ];
    }

    private function getPeriodFromFilter(string $filter): array
    {
        return match($filter) {
            'today' => [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()],
            'week' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()->min(Carbon::now())],
            'month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()->min(Carbon::now())],
            'quarter' => [Carbon::now()->startOfQuarter(), Carbon::now()->endOfQuarter()->min(Carbon::now())],
            'year' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()->min(Carbon::now())],
            'all' => [Carbon::create(2020, 1, 1), Carbon::now()],
            default => [Carbon::now()->startOfMonth(), Carbon::now()],
        };
    }

    private function calculateTrend(int $current, Carbon $start, Carbon $end): array
    {
        $daysDiff = $start->diffInDays($end);
        $previousStart = $start->copy()->subDays($daysDiff);
        $previousEnd = $start->copy()->subDay()->endOfDay();
        
        $previousCount = CandidatesModel::whereBetween('created_at', [$previousStart, $previousEnd])
            ->when($this->educationFilter, fn($q) => $q->where('education_level', $this->educationFilter))
            ->count();
        
        $todayCount = CandidatesModel::whereDate('created_at', Carbon::today())
            ->when($this->educationFilter, fn($q) => $q->where('education_level', $this->educationFilter))
            ->count();
        
        if ($todayCount > 0 && $previousCount == 0) {
            return [
                'text' => 'Novo hoje',
                'icon' => 'heroicon-m-sparkles',
                'color' => 'success',
            ];
        }
        
        if ($previousCount == 0) {
            return [
                'text' => 'Sem dados comparativos',
                'icon' => 'heroicon-m-information-circle',
                'color' => 'gray',
            ];
        }

        $percentChange = (($current - $previousCount) / $previousCount) * 100;
        
        if ($percentChange > 5) {
            return [
                'text' => '+' . number_format($percentChange, 1) . '% vs período anterior',
                'icon' => 'heroicon-m-arrow-trending-up',
                'color' => 'success',
            ];
        } elseif ($percentChange < -5) {
            return [
                'text' => number_format($percentChange, 1) . '% vs período anterior',
                'icon' => 'heroicon-m-arrow-trending-down',
                'color' => 'danger',
            ];
        } else {
            return [
                'text' => 'Estável vs período anterior',
                'icon' => 'heroicon-m-minus',
                'color' => 'warning',
            ];
        }
    }

    private function getChartData(Carbon $start, Carbon $end): array
    {
        $data = [];
        $current = max($start, Carbon::now()->subDays(6))->startOfDay();
        $endDate = min($end, Carbon::now())->endOfDay();
        
        while ($current <= $endDate) {
            $dayStart = $current->copy()->startOfDay();
            $dayEnd = $current->copy()->endOfDay();
            
            $count = CandidatesModel::whereBetween('created_at', [$dayStart, $dayEnd])
                ->when($this->educationFilter, fn($q) => $q->where('education_level', $this->educationFilter))
                ->count();
                
            $data[] = $count;
            $current->addDay();
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
    
    #[On('refresh-candidates-stats')]
    public function updateStats($event): void
    {
        $this->filter = $event['period'] ?? $this->filter;
        $this->educationFilter = !empty($event['educationLevel']) ? $event['educationLevel'] : null;
    }
}
