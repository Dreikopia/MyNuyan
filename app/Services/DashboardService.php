<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Complaint;

class DashboardService
{
    public function monthlyComplaints()
    {
        $monthlyComplaints = Complaint::query()
            ->selectRaw('
                YEAR(created_at) as year,
                MONTH(created_at) as month,
                COUNT(*) as total
            ')
            ->where(
                'created_at',
                '>=',
                now()->subMonths(11)->startOfMonth()
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(fn ($item) => $item->year.'-'.$item->month);

        $months = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);

            $key = $date->year.'-'.$date->month;

            $months->push([
                'month' => $date->format('M Y'),
                'total' => $monthlyComplaints->get($key)?->total ?? 0,
            ]);
        }

        return $months;
    }

    public function complaintsByCategory()
    {
        return Complaint::query()
            ->with('category')
            ->selectRaw('complaint_category_id, COUNT(*) as total')
            ->groupBy('complaint_category_id')
            ->get()
            ->map(fn ($complaint) => [
                'category' => $complaint->category->name,
                'total' => $complaint->total,
            ]);
    }
}
