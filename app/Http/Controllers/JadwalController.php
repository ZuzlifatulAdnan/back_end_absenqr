<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'sekolah';

        $keyword = trim($request->input('nama'));
        $filterKelas = $request->kelas_id;
        $filterGuru = $request->guru_id;
        $filterHari = $request->hari;

        $jadwal = Jadwal::with(['guru.user', 'kelas', 'mapel'])
            ->when($keyword, function ($query, $keyword) {
                $query->whereHas('guru.user', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                })->orWhereHas('mapel', function ($q) use ($keyword) {
                    $q->where('nama', 'like', '%' . $keyword . '%');
                });
            })
            ->when($filterKelas, function ($query, $filterKelas) {
                $query->where('kelas_id', $filterKelas);
            })
            ->when($filterGuru, function ($query, $filterGuru) {
                $query->where('guru_id', $filterGuru);
            })
            ->when($filterHari, function ($query, $filterHari) {
                $query->where('hari', $filterHari);
            })
            ->latest()
            ->paginate(10)
            ->appends([
                'nama' => $keyword,
                'kelas_id' => $filterKelas,
                'guru_id' => $filterGuru,
                'hari' => $filterHari,
            ]);

        // Data untuk filter dropdown
        $kelasList = Kelas::all();
        $guruList = Guru::with('user')->get();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('pages.jadwal.index', compact(
            'type_menu',
            'jadwal',
            'kelasList',
            'guruList',
            'hariList',
            'keyword',
            'filterKelas',
            'filterGuru',
            'filterHari'
        ));
    }

    public function create()
    {
        $type_menu = 'sekolah';
        $guru = Guru::with('user')->get();
        $mapel = Mapel::all();
        $kelas = Kelas::all();

        return view('pages.jadwal.create', compact('type_menu', 'guru', 'mapel', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapels,id',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $jadwal = Jadwal::create($request->only([
            'guru_id',
            'kelas_id',
            'mapel_id',
            'hari',
            'jam_mulai',
            'jam_selesai'
        ]));

        return Redirect::route('jadwal.index')->with('success', 'Jadwal pelajaran untuk kelas ' . $jadwal->kelas->nama . ' berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        $type_menu = 'sekolah';
        $guru = Guru::with('user')->get();
        $mapel = Mapel::all();
        $kelas = Kelas::all();

        return view('pages.jadwal.edit', compact('type_menu', 'jadwal', 'guru', 'mapel', 'kelas'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'guru_id' => 'required|exists:gurus,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapels,id',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $jadwal->update($request->only([
            'guru_id',
            'kelas_id',
            'mapel_id',
            'hari',
            'jam_mulai',
            'jam_selesai'
        ]));

        return Redirect::route('jadwal.index')->with('success', 'Jadwal pelajaran untuk kelas ' . $jadwal->kelas->nama . ' berhasil diubah.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();

        return Redirect::route('jadwal.index')->with('success', 'Jadwal pelajaran untuk kelas ' . $jadwal->kelas->nama . ' berhasil dihapus.');
    }
}
