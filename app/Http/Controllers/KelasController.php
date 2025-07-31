<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'sekolah';
        $keyword = trim($request->input('nama'));
        $kelas = Kelas::when($request->nama, function ($query, $nama) {
            $query->where('nama', 'like', '%' . $nama . '%');
        })->latest()->paginate(10);

        $kelas->appends(['nama' => $keyword]);

        return view('pages.kelas.index', compact('type_menu', 'kelas'));
    }

    public function create()
    {
        $type_menu = 'sekolah';

        return view('pages.kelas.create', compact('type_menu'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'nama' => 'required|string',
            'status' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius' => 'required',
        ]);

        $kelas = Kelas::create([
            'nama' => $request->nama,
            'status' => $request->status,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
        ]);

        return Redirect::route('kelas.index')->with('success', 'Kelas ' . $kelas->nama . ' berhasil di tambah.');
    }

    public function edit(Kelas $kelas)
    {
        $type_menu = 'sekolah';

        return view('pages.kelas.edit', compact('kelas', 'type_menu'));

    }
    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama' => 'required|string',
            'status' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius' => 'required',
        ]);

        $kelas->update([
            'nama' => $request->nama,
            'status' => $request->status,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
        ]);

        return Redirect::route('kelas.index')->with('success', 'Kelas ' . $kelas->nama . ' UKM berhasil di ubah.');
    }
    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return Redirect::route('kelas.index')->with('success', 'Kelas ' . $kelas->nama . ' UKM berhasil di hapus.');
    }
}
