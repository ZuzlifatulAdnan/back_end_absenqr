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

        // Hari-hari unik dari tabel jadwal
        $daftar_hari = Jadwal::select('hari')->distinct()->pluck('hari');

        $user = Auth::user();
        $role = strtolower($user->role); // lowercase
        $filterHari = $request->input('hari');

        $jadwalQuery = Jadwal::with(['mapel', 'kelas', 'guru.user'])->orderBy('jam_mulai');

        // Filter berdasarkan role
        if ($role === 'guru') {
            $guru_id = optional($user->guru)->id;
            $jadwalQuery->where('guru_id', $guru_id ?: 0);
        } elseif ($role === 'siswa') {
            $kelas_id = optional($user->siswa)->kelas_id;
            $jadwalQuery->where('kelas_id', $kelas_id ?: 0);
        }

        // Filter hari
        if ($filterHari) {
            $jadwalQuery->where('hari', $filterHari);
        }

        $jadwal = $jadwalQuery->get();

        // Urutan hari manual
        $urutanHari = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 7,
        ];

        // Urutkan berdasarkan urutan hari dan jam_mulai
        $jadwal = $jadwal->sortBy(function ($item) use ($urutanHari) {
            return ($urutanHari[$item->hari] ?? 999) . $item->jam_mulai;
        });

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

