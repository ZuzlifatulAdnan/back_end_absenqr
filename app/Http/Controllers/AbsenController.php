<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Jadwal::with(['kelas', 'mapel', 'guru.user']);

        // Role: Guru
        if ($user->role == 'guru') {
            $guru = $user->Guru->first();
            if ($guru) {
                $query->where('guru_id', $guru->id);
            } else {
                $jadwals = collect();
                return view('pages.absen.index', [
                    'type_menu' => 'absen',
                    'jadwals' => $jadwals,
                    'kelasList' => [],
                    'selectedKelas' => null,
                    'selectedHari' => null,
                    'searchQuery' => $request->q,
                ]);
            }

            // Role: Siswa
        } elseif ($user->role == 'siswa') {
            $siswa = Siswa::where('user_id', $user->id)->first();
            if ($siswa) {
                $query->where('kelas_id', $siswa->kelas_id);
            } else {
                $jadwals = collect();
                return view('pages.absen.index', [
                    'type_menu' => 'absen',
                    'jadwals' => $jadwals,
                    'kelasList' => [],
                    'selectedKelas' => null,
                    'selectedHari' => null,
                    'searchQuery' => $request->q,
                ]);
            }
        }

        // 🔍 Search: Nama Guru atau Mapel
        if ($request->filled('q')) {
            $query->where(function ($q1) use ($request) {
                $q1->whereHas('guru.user', function ($q2) use ($request) {
                    $q2->where('name', 'like', '%' . $request->q . '%');
                })->orWhereHas('mapel', function ($q3) use ($request) {
                    $q3->where('nama', 'like', '%' . $request->q . '%');
                });
            });
        }

        // 📌 Filter Kelas (kecuali siswa)
        if ($request->filled('kelas_id') && $user->role != 'siswa') {
            $query->where('kelas_id', $request->kelas_id);
        }

        // 📌 Filter Hari (semua role)
        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        // 🔄 Pagination
        $jadwals = $query->paginate(10)->withQueryString();

        // Daftar Kelas (untuk dropdown filter)
        $kelasList = ($user->role != 'siswa') ? Kelas::all() : [];

        return view('pages.absen.index', [
            'type_menu' => 'absen',
            'jadwals' => $jadwals,
            'kelasList' => $kelasList,
            'selectedKelas' => $request->kelas_id,
            'selectedHari' => $request->hari,
            'searchQuery' => $request->q,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Absen $absen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absen $absen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absen $absen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absen $absen)
    {
        //
    }
}
