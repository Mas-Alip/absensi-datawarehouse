<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAbsensiRequest;
use App\Http\Requests\UpdateAbsensiRequest;
use App\Models\Absensi;
use App\Models\Pegawai;
use App\Enums\LateStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    public function index(): View
    {
        $perPage = 10;
        $search = request('search');
        $status = request('status');
        $sort = request('sort', 'newest');

        $absensi = Absensi::with('pegawai')
            ->when($search, function ($query, $search) {
                $query->whereHas('pegawai', function ($query) use ($search) {
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status_kehadiran', $status);
            })
            ->orderBy('tanggal', $sort === 'oldest' ? 'asc' : 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('absensi.index', compact('absensi', 'search', 'status', 'sort'));
    }

    public function create(): View
    {
        $pegawai = Pegawai::orderBy('nama')->get();

        return view('absensi.create', compact('pegawai'));
    }

    public function store(StoreAbsensiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Business logic: keterlambatan
        $data['status_keterlambatan'] = $this->computeLateStatus($data['jam_masuk'] ?? null);

        Absensi::create($data);

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil ditambahkan.');
    }

    public function edit(Absensi $absensi): View
    {
        $pegawai = Pegawai::orderBy('nama')->get();

        return view('absensi.edit', compact('absensi', 'pegawai'));
    }

    public function update(UpdateAbsensiRequest $request, Absensi $absensi): RedirectResponse
    {
        $data = $request->validated();

        $data['status_keterlambatan'] = $this->computeLateStatus($data['jam_masuk'] ?? null);

        $absensi->update($data);

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy(Absensi $absensi): RedirectResponse
    {
        $absensi->delete();

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dihapus.');
    }

    private function computeLateStatus(?string $jamMasuk): string
    {
        if (! $jamMasuk) {
            return LateStatus::ON_TIME->value; // default when no time provided
        }

        $time = \DateTime::createFromFormat('H:i', $jamMasuk);
        $limit = \DateTime::createFromFormat('H:i', '07:00');

        if (!$time) {
            return LateStatus::ON_TIME->value;
        }

        return $time > $limit ? LateStatus::LATE->value : LateStatus::ON_TIME->value;
    }
}
