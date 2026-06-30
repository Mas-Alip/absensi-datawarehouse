<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdministrasiAlphaController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('pegawai.administrasi-alpha', [
            'user' => $user,
        ]);
    }

    public function downloadPdf(Request $request)
    {
        $user = $request->user();
        $data = [
            'user' => $user,
            'generated_at' => now()->translatedFormat('d F Y, H:i'),
        ];

        $pdf = Pdf::loadView('pegawai.pdf.administrasi-alpha', $data);

        return $pdf->download('administrasi-alpha-' . ($user->name ?? 'pegawai') . '.pdf');
    }
}
