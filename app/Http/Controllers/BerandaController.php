<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'beranda';

        // Statistik
        $jumlah_user = User::count();
        $jumlah_guru = Guru::count();
        $jumlah_siswa = Siswa::count();
        $jumlah_mapel = Mapel::count();

        $daftar_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];


        $user = Auth::user();
        $role = strtolower($user->role);
        $filterHari = $request->input('hari');

        $jadwalQuery = Jadwal::with(['mapel', 'kelas', 'guru.user']);

        if ($role === 'guru') {
            $guru_id = optional($user->guru)->id;
            $jadwalQuery->where('guru_id', $guru_id ?: 0);
        } elseif ($role === 'siswa') {
            $kelas_id = optional($user->siswa)->kelas_id;
            $jadwalQuery->where('kelas_id', $kelas_id ?: 0);
        }

        if ($filterHari) {
            $jadwalQuery->where('hari', $filterHari);
        }

        // Urutan hari manual
        $jadwalQuery->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->orderBy('jam_mulai');

        // Paginate
        $jadwal = $jadwalQuery->paginate(5);

        return view('pages.beranda.index', compact(
            'type_menu',
            'jumlah_user',
            'jumlah_guru',
            'jumlah_siswa',
            'jumlah_mapel',
            'jadwal',
            'daftar_hari',
            'filterHari'
        ));
    }

}

