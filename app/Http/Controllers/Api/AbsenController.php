<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\Absen_Qr;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
class AbsenController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Jadwal::with(['kelas', 'mapel', 'guru.user']);

        if ($user->role === 'Siswa') {
            $query->where('kelas_id', optional($user->siswa)->kelas_id ?? 0);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('guru.user', fn($q2) => $q2->where('name', 'like', "%{$request->q}%"))
                    ->orWhereHas('mapel', fn($q3) => $q3->where('nama', 'like', "%{$request->q}%"));
            });
        }

        if ($request->filled('kelas_id') && $user->role !== 'Siswa') {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        $jadwals = $query->paginate(10);

        return response()->json($jadwals);
    }

    public function show(Request $request, $id)
    {
        $jadwal = Jadwal::with('kelas', 'mapel')->findOrFail($id);

        $allAbsen = Absen::with('jadwal.kelas')
            ->where('jadwal_id', $id)
            ->when(
                $request->filled('hari'),
                fn($q) =>
                $q->whereHas('jadwal', fn($q2) => $q2->where('hari', $request->hari))
            )
            ->when(
                $request->filled('kelas_id'),
                fn($q) =>
                $q->whereHas('jadwal.kelas', fn($q2) => $q2->where('id', $request->kelas_id))
            )
            ->when(
                $request->filled('tanggal'),
                fn($q) =>
                $q->whereDate('tanggal_absen', $request->tanggal)
            )
            ->get();

        $grouped = $allAbsen->groupBy(fn($item) => $item->pertemuan_ke . '|' . $item->tanggal_absen);

        $data = $grouped->map(function ($group) {
            $first = $group->first();
            return [
                'id' => $first->id,
                'pertemuan_ke' => $first->pertemuan_ke,
                'tanggal_absen' => $first->tanggal_absen,
                'jadwal' => $first->jadwal,
                'total_siswa' => $group->count(),
                'total_hadir' => $group->where('status', 'H')->count(),
            ];
        })->values();

        return response()->json($data);
    }

    public function rekap($id)
    {
        $user = Auth::user();
        $jadwal = Jadwal::findOrFail($id);

        $query = Absen::with('siswa')->where('jadwal_id', $id);

        if ($user->role === 'Siswa') {
            $siswa = $user->siswa;
            if (!$siswa) {
                return response()->json(['message' => 'Data siswa tidak ditemukan'], 403);
            }
            $query->where('siswa_id', $siswa->id);
        }

        $absen = $query->get();
        $jumlahPertemuan = $absen->max('pertemuan_ke') ?? 0;

        $tanggalAbsen = [];
        for ($i = 1; $i <= $jumlahPertemuan; $i++) {
            $pert = $absen->where('pertemuan_ke', $i)->first();
            $tanggalAbsen[$i] = $pert && $pert->tanggal_absen ? Carbon::parse($pert->tanggal_absen)->format('d-m-Y') : 'N/A';
        }

        $data = $absen->groupBy('siswa_id');

        return response()->json([
            'jadwal' => $jadwal,
            'jumlah_pertemuan' => $jumlahPertemuan,
            'tanggal_absen' => $tanggalAbsen,
            'rekap' => $data,
        ]);
    }
    public function scanForm()
    {
        $user = Auth::user();

        // Ambil data siswa beserta kelasnya
        $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();

        if (!$siswa || !$siswa->kelas) {
            return response()->json([
                'message' => 'Data kelas tidak ditemukan untuk siswa ini.'
            ], 404);
        }

        return response()->json([
            'message' => 'Data scan form berhasil diambil.',
            'data' => [
                'kelas' => $siswa->kelas
            ]
        ]);
    }

    public function submitScan(Request $request, FonnteService $fonnteService)
    {
        $request->validate([
            'token_qr' => 'required|string',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ]);

        $absenqr = Absen_Qr::where('token_qr', $request->token_qr)
            ->where('expired_at', '>', now())
            ->first();

        if (!$absenqr) {
            return response()->json(['message' => 'QR tidak valid atau sudah kadaluarsa.'], 400);
        }

        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) {
            return response()->json(['message' => 'Siswa tidak ditemukan.'], 404);
        }

        $kelas = $siswa->kelas;
        $jarak = $this->hitungJarak($kelas->latitude, $kelas->longitude, $request->lat, $request->long);

        if ($jarak > $kelas->radius) {
            return response()->json(['message' => 'Anda berada di luar radius yang diizinkan.'], 403);
        }

        $cekAbsen = Absen::where('siswa_id', $siswa->id)
            ->where('jadwal_id', $absenqr->jadwal_id)
            ->whereDate('tanggal_absen', now())
            ->first();

        if ($cekAbsen) {
            return response()->json(['message' => 'Anda sudah absen hari ini.'], 409);
        }

        $pertemuan = Absen::where('siswa_id', $siswa->id)
            ->where('jadwal_id', $absenqr->jadwal_id)
            ->count() + 1;

        $absen = Absen::create([
            'jadwal_id' => $absenqr->jadwal_id,
            'siswa_id' => $siswa->id,
            'absenqr_id' => $absenqr->id,
            'tanggal_absen' => now(),
            'latitude' => $request->lat,
            'longitude' => $request->long,
            'pertemuan_ke' => $pertemuan,
            'status' => 'H'
        ]);

        // Notifikasi
        $ortu = OrangTua::where('siswa_id', $siswa->id)->first();
        if ($ortu && $ortu->no_telepon) {
            $fonnteService->sendMessage($ortu->no_telepon, "📢 *Notifikasi Absensi*\n\n"
                . "Halo, *{$ortu->nama}*.\n\n"
                . "Anak Anda, *{$siswa->user->name}*, telah melakukan absensi pada:\n"
                . "📅 Tanggal: *" . now()->format('d-m-Y') . "*\n"
                . "⏰ Jam: *" . now()->format('H:i') . "*\n"
                . "📚 Mata Pelajaran: *" . ($absenqr->jadwal->mapel->nama ?? 'Pelajaran') . "*\n\n"
                . "Terima kasih.");
        }

        return response()->json(['message' => 'Absen berhasil direkam.']);
    }

    private function hitungJarak($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
