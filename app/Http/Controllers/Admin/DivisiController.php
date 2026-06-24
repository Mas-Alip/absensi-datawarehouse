<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDivisiRequest;
use App\Http\Requests\UpdateDivisiRequest;
use App\Models\Divisi;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DivisiController extends Controller
{
    public function index(): View
    {
        $divisis = Divisi::orderBy('nama_divisi')->get();

        return view('divisi.index', compact('divisis'));
    }

    public function create(): View
    {
        return view('divisi.create');
    }

    public function store(StoreDivisiRequest $request): RedirectResponse
    {
        Divisi::create($request->validated());

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit(Divisi $divisi): View
    {
        return view('divisi.edit', compact('divisi'));
    }

    public function update(UpdateDivisiRequest $request, Divisi $divisi): RedirectResponse
    {
        $divisi->update($request->validated());

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Divisi $divisi): RedirectResponse
    {
        $divisi->delete();

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil dihapus.');
    }
}
