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

        $jumlah_user = User::count();
        $jumlah_guru = Guru::count();
        $jumlah_siswa = Siswa::count();
        $jumlah_mapel = Mapel::count();

        $tanggal = $request->input('tanggal') ?? now()->format('Y-m-d');

        // Selalu tentukan hari dari tanggal
        $hari = Carbon::parse($tanggal)->locale('id')->translatedFormat('l');

        // Kalau user pilih hari manual, pakai itu
        if ($request->filled('hari')) {
            $hari = $request->hari;
        }

        $jadwal = Jadwal::where('hari', $hari);

        if (Auth::user()->role == 'Guru') {
            $jadwal->where('guru_id', Auth::user()->guru->id);
        } elseif (Auth::user()->role == 'Siswa') {
            $jadwal->where('kelas_id', Auth::user()->siswa->kelas_id);
        }

        $jadwal = $jadwal->with(['mapel', 'kelas', 'guru'])
            ->orderBy('jam_mulai')
            ->get();

        $tanggal_formatted = Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y');

        return view('pages.beranda.index', compact(
            'type_menu',
            'jumlah_user',
            'jumlah_guru',
            'jumlah_siswa',
            'jumlah_mapel',
            'jadwal',
            'tanggal',
            'hari',
            'tanggal_formatted'
        ));
    }
}
