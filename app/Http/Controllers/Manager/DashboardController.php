<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Services\ManagerAnalyticsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request, ManagerAnalyticsService $analytics): View
    {
        $filters = $analytics->parseFilters($request);
        $data = $analytics->dashboardData($filters);

        return view('manager.dashboard', array_merge($data, ['filters' => $filters]));
    }

    public function exportPdf(Request $request, ManagerAnalyticsService $analytics)
    {
        $filters = $analytics->parseFilters($request);
        $data = $analytics->dashboardData($filters);

        $pdf = Pdf::loadView('manager.pdf.report', array_merge($data, ['filters' => $filters]));

        return $pdf->download(sprintf('dashboard-analytics-%s.pdf', $filters['start_date']->format('Ymd')));
    }
}
