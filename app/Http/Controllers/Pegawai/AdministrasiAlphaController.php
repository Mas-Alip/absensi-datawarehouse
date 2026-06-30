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
        $validated = $request->validate([
            'keterangan' => ['required', 'string', 'min:10'],
        ], [
            'keterangan.required' => 'Keterangan wajib diisi.',
            'keterangan.min' => 'Keterangan minimal 10 karakter.',
        ]);

        $user = $request->user();
        $data = [
            'user' => $user,
            'generated_at' => now()->translatedFormat('d F Y, H:i'),
            'printed_date' => 'Pemalang, ' . now()->locale('id')->translatedFormat('d F Y'),
            'keterangan' => $validated['keterangan'],
        ];

        $pdf = Pdf::loadView('pegawai.pdf.administrasi-alpha', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('administrasi-alpha-' . ($user->name ?? 'pegawai') . '.pdf');
    }
}
