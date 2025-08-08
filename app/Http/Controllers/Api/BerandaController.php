<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = strtolower($user->role);
        $filterHari = $request->input('hari');

        $hariMap = [
            'Senin' => 0,
            'Selasa' => 1,
            'Rabu' => 2,
            'Kamis' => 3,
            'Jumat' => 4,
            'Sabtu' => 5,
            'Minggu' => 6,
        ];

        // Statistik (khusus Admin)
        $statistik = null;
        if ($role === 'admin') {
            $statistik = [
                'jumlah_user' => User::count(),
                'jumlah_guru' => Guru::count(),
                'jumlah_siswa' => Siswa::count(),
                'jumlah_mapel' => Mapel::count(),
            ];
        }

        $jadwalQuery = Jadwal::with(['mapel', 'kelas', 'guru.user']);

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

        // Urutkan hari & jam
        $jadwalQuery->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->orderBy('jam_mulai');

        // Paginate hasil jadwal
        $jadwal = $jadwalQuery->paginate(5);
        $jadwal->through(function ($item) use ($hariMap) {
            $indexHari = $hariMap[$item->hari] ?? 0;
            $tanggalPertemuan = Carbon::now()->startOfWeek(Carbon::MONDAY)->addDays($indexHari)->format('d-m-Y');

            // Menambahkan properti baru ke objek item
            $item->tanggal_pertemuan = $tanggalPertemuan;

            return $item;
        });
        return response()->json([
            'success' => true,
            'message' => 'Data beranda berhasil diambil.',
            'role' => $user->role,
            'statistik' => $statistik,
            'jadwal' => $jadwal,
            'user' => [
                'name' => $user->name,
                'image' => $user->image
                    ? url('/img/user/' . $user->image)
                    : null,
            ],
        ]);
    }
}
