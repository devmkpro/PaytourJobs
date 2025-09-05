<?php

namespace App\Filament\Widgets;

use App\Models\Candidates as CandidatesModel;
use App\Enums\EducationLevel;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\On;

class CandidatesEducationWidget extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '60s';
    protected static ?int $sort = 2;
    protected static bool $isLazy = false;

    public ?string $filter = 'month';
    public ?string $positionFilter = null;

    public function getHeading(): ?string
    {
        return 'Breakdown por Nível Educacional';
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
            'educationLevel' => null,
        ]);
    }

    protected function getStats(): array
    {
        $activeFilter = $this->filter;
        [$start, $end] = $this->getPeriodFromFilter($activeFilter);
        $baseQuery = CandidatesModel::whereBetween('created_at', [$start, $end]);
        
        if ($this->positionFilter) {
            $baseQuery->where('desired_position', 'like', '%' . $this->positionFilter . '%');
        }
        
        $totalCandidates = $baseQuery->count();
        $educationStats = $baseQuery->selectRaw('education_level, COUNT(*) as count')
            ->groupBy('education_level')
            ->get()
            ->keyBy('education_level');

        $stats = [];
        
        foreach (EducationLevel::cases() as $level) {
            $count = $educationStats->get($level->value)?->count ?? 0;
            $percentage = $totalCandidates > 0 ? round(($count / $totalCandidates) * 100, 1) : 0;
            $trend = $this->calculateTrendForEducationLevel($level, $start, $end);
            $chartData = $this->getEducationLevelChartData($level, $start, $end);
            $color = match($level) {
                EducationLevel::FUNDAMENTAL => 'danger',
                EducationLevel::MEDIO => 'warning',
                EducationLevel::SUPERIOR => 'success',
                EducationLevel::POS_GRADUACAO => 'primary',
            };
            
            $stats[] = Stat::make(
                $this->getEducationLevelLabel($level->value), 
                $count
            )
                ->description($percentage . '% do total - ' . $trend['text'])
                ->descriptionIcon($trend['icon'])
                ->chart($chartData)
                ->color($color);
        }
        
        return $stats;
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

    private function calculateTrendForEducationLevel(EducationLevel $level, Carbon $start, Carbon $end): array
    {
        $daysDiff = $start->diffInDays($end);
        $previousStart = $start->copy()->subDays($daysDiff);
        $previousEnd = $start->copy()->subDay()->endOfDay();
        
        $currentCount = CandidatesModel::whereBetween('created_at', [$start, $end])
            ->where('education_level', $level->value)
            ->when($this->positionFilter, fn($q) => $q->where('desired_position', 'like', '%' . $this->positionFilter . '%'))
            ->count();
            
        $previousCount = CandidatesModel::whereBetween('created_at', [$previousStart, $previousEnd])
            ->where('education_level', $level->value)
            ->when($this->positionFilter, fn($q) => $q->where('desired_position', 'like', '%' . $this->positionFilter . '%'))
            ->count();
        
        $todayCount = CandidatesModel::whereDate('created_at', Carbon::today())
            ->where('education_level', $level->value)
            ->when($this->positionFilter, fn($q) => $q->where('desired_position', 'like', '%' . $this->positionFilter . '%'))
            ->count();
        
        if ($todayCount > 0 && $previousCount == 0) {
            return [
                'text' => 'Novo',
                'icon' => 'heroicon-m-sparkles',
            ];
        }
        
        if ($previousCount == 0) {
            return [
                'text' => 'Sem dados anteriores',
                'icon' => 'heroicon-m-information-circle',
            ];
        }

        $percentChange = (($currentCount - $previousCount) / $previousCount) * 100;
        
        if ($percentChange > 10) {
            return [
                'text' => '+' . number_format($percentChange, 1) . '%',
                'icon' => 'heroicon-m-arrow-trending-up',
            ];
        } elseif ($percentChange < -10) {
            return [
                'text' => number_format($percentChange, 1) . '%',
                'icon' => 'heroicon-m-arrow-trending-down',
            ];
        } else {
            return [
                'text' => 'Estável',
                'icon' => 'heroicon-m-minus',
            ];
        }
    }

    private function getEducationLevelChartData(EducationLevel $level, Carbon $start, Carbon $end): array
    {
        $data = [];
        $current = max($start, Carbon::now()->subDays(6))->startOfDay();
        $endDate = min($end, Carbon::now())->endOfDay();
        
        while ($current <= $endDate) {
            $dayStart = $current->copy()->startOfDay();
            $dayEnd = $current->copy()->endOfDay();
            
            $count = CandidatesModel::whereBetween('created_at', [$dayStart, $dayEnd])
                ->where('education_level', $level->value)
                ->when($this->positionFilter, fn($q) => $q->where('desired_position', 'like', '%' . $this->positionFilter . '%'))
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
    
    #[On('refresh-education-widget')]
    public function updateStats($event): void
    {
        $this->filter = $event['period'] ?? $this->filter;
        $this->positionFilter = !empty($event['position']) ? $event['position'] : null;
    }
}
