<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apd;
use App\Models\Hazard;
use App\Models\HealthProgram;
use App\Models\Incident;
use App\Models\SopStep;
use App\Models\TeamMember;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'apd'      => Apd::count(),
            'sop'      => SopStep::count(),
            'hazard'   => Hazard::count(),
            'incident' => Incident::count(),
            'team'     => TeamMember::count(),
            'health'   => HealthProgram::count(),
        ];

        // Bar chart: jumlah hazard per kategori
        $hazardByCategory = Hazard::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        $hazardChart = [
            'labels' => [],
            'data'   => [],
        ];
        foreach (Hazard::CATEGORIES as $key => $label) {
            $hazardChart['labels'][] = $label;
            $hazardChart['data'][]   = (int) ($hazardByCategory[$key] ?? 0);
        }

        // Doughnut chart: status insiden
        $incidentByStatus = Incident::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusChart = [
            'labels' => [],
            'data'   => [],
        ];
        foreach (Incident::STATUSES as $key => $label) {
            $statusChart['labels'][] = $label;
            $statusChart['data'][]   = (int) ($incidentByStatus[$key] ?? 0);
        }

        // Line chart: tren insiden 12 bulan terakhir
        $trend = ['labels' => [], 'data' => []];
        $start = Carbon::now()->startOfMonth()->subMonths(11);

        $monthly = Incident::select(
                DB::raw("to_char(incident_date, 'YYYY-MM') as ym"),
                DB::raw('count(*) as total')
            )
            ->whereNotNull('incident_date')
            ->where('incident_date', '>=', $start->toDateString())
            ->groupBy('ym')
            ->pluck('total', 'ym');

        for ($i = 0; $i < 12; $i++) {
            $month = (clone $start)->addMonths($i);
            $key = $month->format('Y-m');
            $trend['labels'][] = $month->translatedFormat('M Y');
            $trend['data'][]   = (int) ($monthly[$key] ?? 0);
        }

        $recentIncidents = Incident::latest('created_at')->take(5)->get();

        return view('admin.dashboard', compact(
            'counts',
            'hazardChart',
            'statusChart',
            'trend',
            'recentIncidents'
        ));
    }
}
