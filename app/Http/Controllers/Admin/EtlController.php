<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WarehouseEtlService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EtlController extends Controller
{
    public function __invoke(Request $request, WarehouseEtlService $etlService): RedirectResponse
    {
        $etlService->loadWarehouse();

        return redirect()->back()->with('success', 'ETL data warehouse berhasil dijalankan. Semua tabel warehouse telah diperbarui.');
    }
}
