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

        // Ambil semua hari yang ada di jadwal
        $daftar_hari = Jadwal::select('hari')->distinct()->pluck('hari');

        $user = Auth::user();
        $role = $user->role;

        // Filter hari (dari dropdown)
        $filterHari = $request->input('hari');

        $jadwalQuery = Jadwal::query();

        if ($role === 'Guru') {
            $guru_id = optional($user->guru)->id;
            if ($guru_id) {
                $jadwalQuery->where('guru_id', $guru_id);
            } else {
                $jadwalQuery = collect(); // Tidak ada jadwal
            }
        } elseif ($role === 'Siswa') {
            $kelas_id = optional($user->siswa)->kelas_id;
            if ($kelas_id) {
                $jadwalQuery->where('kelas_id', $kelas_id);
            } else {
                $jadwalQuery = collect(); // Tidak ada jadwal
            }
        }

        // Jika user bukan siswa/guru atau sudah ada query builder
        if ($jadwalQuery instanceof \Illuminate\Database\Eloquent\Builder) {
            if ($filterHari) {
                $jadwalQuery->where('hari', $filterHari);
            }

            $jadwal = $jadwalQuery->with(['mapel', 'kelas', 'guru'])
                ->orderBy('jam_mulai')
                ->get();
        } else {
            $jadwal = collect(); // Kosongkan
        }

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
