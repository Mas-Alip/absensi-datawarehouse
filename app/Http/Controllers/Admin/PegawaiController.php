<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePegawaiRequest;
use App\Http\Requests\UpdatePegawaiRequest;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PegawaiController extends Controller
{
    public function index(): View
    {
        $pegawai = Pegawai::with(['divisi', 'jabatan'])->orderBy('nama')->get();

        return view('pegawai.index', compact('pegawai'));
    }

    public function create(): View
    {
        $divisi = Divisi::orderBy('nama_divisi')->get();
        $jabatan = Jabatan::orderBy('nama_jabatan')->get();

        return view('pegawai.create', compact('divisi', 'jabatan'));
    }

    public function store(StorePegawaiRequest $request): RedirectResponse
    {
        Pegawai::create($request->validated());

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai): View
    {
        $divisi = Divisi::orderBy('nama_divisi')->get();
        $jabatan = Jabatan::orderBy('nama_jabatan')->get();

        return view('pegawai.edit', compact('pegawai', 'divisi', 'jabatan'));
    }

    public function update(UpdatePegawaiRequest $request, Pegawai $pegawai): RedirectResponse
    {
        $pegawai->update($request->validated());

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai): RedirectResponse
    {
        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
    }

    public function createAccount(Pegawai $pegawai): View
    {
        if ($pegawai->user_id) {
            return redirect()->route('pegawai.index')->with('error', 'Pegawai ini sudah memiliki akun login.');
        }

        return view('pegawai.create-account', compact('pegawai'));
    }

    public function storeAccount(Request $request, Pegawai $pegawai): RedirectResponse
    {
        if ($pegawai->user_id) {
            return redirect()->route('pegawai.index')->with('error', 'Pegawai ini sudah memiliki akun login.');
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $pegawai->nama,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => UserRole::PEGAWAI,
        ]);

        $pegawai->update(['user_id' => $user->id]);

        return redirect()->route('pegawai.index')->with('success', 'Akun login berhasil dibuat untuk ' . $pegawai->nama);
    }
}
