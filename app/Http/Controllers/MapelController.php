<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'sekolah';
        $keyword = trim($request->input('nama'));

        $mapel = Mapel::with('guru.user')
            ->when($keyword, function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', '%' . $keyword . '%')
                        ->orWhereHas('guru.user', function ($sub) use ($keyword) {
                            $sub->where('name', 'like', '%' . $keyword . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10);

        $mapel->appends(['nama' => $keyword]);

        return view('pages.mapel.index', compact('type_menu', 'mapel'));
    }

    public function create()
    {
        $type_menu = 'sekolah';
        $gurus = Guru::with('user')->get();

        return view('pages.mapel.create', compact('type_menu', 'gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:mapels,kode',
        ]);

        $mapel = Mapel::create([
            'guru_id' => $request->guru_id,
            'nama' => $request->nama,
            'kode' => $request->kode,
        ]);

        return Redirect::route('mapel.index')
            ->with('success', 'Mata Pelajaran ' . $mapel->nama . ' berhasil ditambahkan.');
    }

    public function edit(Mapel $mapel)
    {
        $type_menu = 'sekolah';
        $gurus = Guru::with('user')->get();

        return view('pages.mapel.edit', compact('type_menu', 'mapel', 'gurus'));
    }

    public function update(Request $request, Mapel $mapel)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:mapels,kode,' . $mapel->id,
        ]);

        $mapel->update([
            'guru_id' => $request->guru_id,
            'nama' => $request->nama,
            'kode' => $request->kode,
        ]);

        return Redirect::route('mapel.index')
            ->with('success', 'Mata Pelajaran ' . $mapel->nama . ' berhasil diubah.');
    }

    public function destroy(Mapel $mapel)
    {
        $mapel->delete();

        return Redirect::route('mapel.index')
            ->with('success', 'Mata Pelajaran ' . $mapel->nama . ' berhasil dihapus.');
    }
}
