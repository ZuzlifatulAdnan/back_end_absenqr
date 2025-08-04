<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Absen_Qr;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Cek role dan relasi
        if ($user->role === 'guru') {
            $guru = $user->guru;
            if (!$guru) {
                dd('Guru tidak ditemukan untuk user ID ' . $user->id);
            }
        } elseif ($user->role === 'siswa') {
            $siswa = $user->siswa;
            if (!$siswa) {
                dd('Siswa tidak ditemukan untuk user ID ' . $user->id);
            }
        }

        // Mulai query
        $query = Jadwal::with(['kelas', 'mapel', 'guru.user']);

        if ($user->role === 'Guru') {
            $guru = $user->guru;
            $query->where('guru_id', $guru->id);
        } elseif ($user->role === 'Siswa') {
            $siswa = $user->siswa;
            $query->where('kelas_id', $siswa->kelas_id);
        }

        // Filter dan search
        if ($request->filled('q')) {
            $query->where(function ($q1) use ($request) {
                $q1->whereHas('guru.user', function ($q2) use ($request) {
                    $q2->where('name', 'like', '%' . $request->q . '%');
                })->orWhereHas('mapel', function ($q3) use ($request) {
                    $q3->where('nama', 'like', '%' . $request->q . '%');
                });
            });
        }

        if ($request->filled('kelas_id') && $user->role !== 'siswa') {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        $jadwals = $query->paginate(10)->withQueryString();
        $kelasList = ($user->role !== 'Siswa') ? Kelas::all() : [];

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
    public function add(Jadwal $absen)
    {
        $siswas = Siswa::with('user')->where('kelas_id', $absen->kelas_id)->get();

        $lastAbsen = Absen::where('jadwal_id', $absen->id)->latest('pertemuan_ke')->first();
        $pertemuan = $lastAbsen ? $lastAbsen->pertemuan_ke + 1 : 1;

        return view('pages.absen.create', [
            'type_menu' => 'absen',
            'jadwals' => $absen,
            'siswas' => $siswas,
            'pertemuan' => $pertemuan
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|array',
            'kehadiran' => 'required|array',
            'kehadiran.*' => 'in:H,I,S,A',
            'jadwal_id' => 'required|integer|exists:jadwals,id',
        ]);

        $siswaIds = $request->input('siswa_id');
        $kehadiran = $request->input('kehadiran');
        $jadwalId = $request->input('jadwal_id');

        foreach ($siswaIds as $siswaId) {
            Absen::create([
                'tanggal_absen' => now(),
                'status' => $kehadiran[$siswaId],
                'pertemuan_ke' => $request->input('pertemuan_ke'),
                'jadwal_id' => $jadwalId,
                'siswa_id' => $siswaId,
            ]);
        }

        return redirect()->route('absen.index')->with('success', 'Data absensi berhasil disimpan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $jadwal = Jadwal::with('kelas', 'mapel')->findOrFail($id);
        $kelasList = Kelas::orderBy('nama')->get(); // Ambil daftar kelas

        // Ambil semua data absen yang sesuai filter
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

        // Group berdasarkan pertemuan dan tanggal
        $grouped = $allAbsen->groupBy(fn($item) => $item->pertemuan_ke . '|' . $item->tanggal_absen);

        // Buat data koleksi baru
        $data = $grouped->map(function ($group) {
            $first = $group->first();
            return (object) [
                'id' => $first->id,
                'pertemuan_ke' => $first->pertemuan_ke,
                'tanggal_absen' => $first->tanggal_absen,
                'jadwal' => $first->jadwal,
                'total_siswa' => $group->count(),
                'total_hadir' => $group->where('status', 'H')->count(),
            ];
        })->values(); // Reset index

        // Manual pagination
        $page = $request->input('page', 1);
        $perPage = 10;
        $pagedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $type_menu = 'absen';

        return view('pages.absen.show', [
            'type_menu' => $type_menu,
            'absen' => $pagedData,
            'jadwal' => $jadwal,
            'kelasList' => $kelasList,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $absen = Absen::with('jadwal.kelas', 'jadwal.mapel')->findOrFail($id);
        $jadwal = $absen->jadwal;
        $type_menu = 'absen';

        // Ambil semua siswa di kelas tersebut
        $siswas = Siswa::where('kelas_id', $jadwal->kelas_id)->with('user')->get();

        // Ambil semua data absensi untuk jadwal dan pertemuan ini
        $absensi = Absen::where('jadwal_id', $jadwal->id)
            ->where('pertemuan_ke', $absen->pertemuan_ke)
            ->get()
            ->keyBy('siswa_id');

        return view('pages.absen.edit', compact('type_menu', 'absen', 'jadwal', 'siswas', 'absensi'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'siswa_id' => 'required|array',
            'kehadiran' => 'required|array',
            'kehadiran.*' => 'in:H,I,S,A',
        ]);

        $absen = Absen::findOrFail($id);

        $siswaIds = $request->input('siswa_id');
        $kehadiran = $request->input('kehadiran');

        foreach ($siswaIds as $siswaId) {
            $status = $kehadiran[$siswaId] ?? null;

            if ($status) {
                Absen::updateOrCreate(
                    [
                        'jadwal_id' => $absen->jadwal_id,
                        'siswa_id' => $siswaId,
                        'pertemuan_ke' => $absen->pertemuan_ke,
                    ],
                    [
                        'tanggal_absen' => $absen->tanggal_absen ?? now(),
                        'status' => $status,
                        'absenqr_id' => $absen->absenqr_id, // tambahkan jika penting
                    ]
                );
            }
        }

        return redirect()->route('absen.show', $absen->jadwal_id)
            ->with('success', 'Data absensi berhasil diperbarui.');
    }
    public function rekap($id)
    {
        $user = Auth::user();

        // Ambil data jadwal
        $jadwal = Jadwal::findOrFail($id);

        // Query dasar
        $absenQuery = Absen::with('Siswa')->where('jadwal_id', $id);

        // Jika siswa, filter hanya absensinya sendiri
        if ($user->role === 'Siswa') {
            $siswa = $user->siswa;
            if (!$siswa) {
                abort(403, 'Data siswa tidak ditemukan untuk user ID ' . $user->id);
            }

            $absenQuery->where('siswa_id', $siswa->id);
        }

        // Eksekusi query
        $absen = $absenQuery->get();

        // Hitung jumlah pertemuan maksimal
        $jumlahPertemuan = $absen->max('pertemuan_ke') ?? 0;

        // Susun tanggal pertemuan
        $tanggalAbsen = [];
        for ($i = 1; $i <= $jumlahPertemuan; $i++) {
            $pertemuan = $absen->where('pertemuan_ke', $i)->first();
            if ($pertemuan && $pertemuan->tanggal_absen) {
                $tanggalAbsen[$i] = \Carbon\Carbon::parse($pertemuan->tanggal_absen)->format('d-m-Y');
            } else {
                $tanggalAbsen[$i] = 'N/A';
            }
        }

        // Kelompokkan absensi berdasarkan siswa
        $absens = $absen->groupBy('siswa_id');

        // Set menu aktif
        $type_menu = 'absen';

        // Kirim ke view
        return view('pages.absen.rekap', compact('type_menu', 'absens', 'jadwal', 'jumlahPertemuan', 'tanggalAbsen'));
    }

    public function scanForm()
    {
        $type_menu = 'absen';
        $user = Auth::user();

        // Ambil data siswa berdasarkan user_id
        $siswa = Siswa::where('user_id', $user->id)->first();

        if (!$siswa || !$siswa->kelas) {
            abort(404, 'Data kelas tidak ditemukan untuk siswa ini.');
        }

        $kelas = $siswa->kelas;

        return view('pages.absen.scan', compact('type_menu', 'kelas'));
    }

    public function submitScan(Request $request, FonnteService $fonnteService)
    {
        $request->validate([
            'token_qr' => 'required|string',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ]);

        $token = $request->token_qr;

        $absenqr = Absen_Qr::where('token_qr', $token)
            ->where('expired_at', '>', Carbon::now())
            ->first();

        if (!$absenqr) {
            return back()->with('error', 'QR tidak valid atau sudah kadaluarsa.');
        }
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) {
            return back()->with('error', 'Siswa tidak ditemukan.');
        }

        $kelas = $siswa->kelas;
        $jarak = $this->hitungJarak($kelas->latitude, $kelas->longitude, $request->lat, $request->long);

        if ($jarak > $kelas->radius) {
            return back()->with('error', 'Anda berada di luar radius yang diizinkan.');
        }

        // Cek apakah sudah absen hari ini
        $cekAbsen = Absen::where('siswa_id', $siswa->id)
            ->where('jadwal_id', $absenqr->jadwal_id)
            ->whereDate('tanggal_absen', now())
            ->first();

        if ($cekAbsen) {
            return back()->with('error', 'Anda sudah absen hari ini.');
        }

        $pertemuan = Absen::where('siswa_id', $siswa->id)
            ->where('jadwal_id', $absenqr->jadwal_id)
            ->count() + 1;

        Absen::create([
            'jadwal_id' => $absenqr->jadwal_id,
            'siswa_id' => $siswa->id,
            'absenqr_id' => $absenqr->id,
            'tanggal_absen' => now(),
            'latitude' => $request->lat,
            'longitude' => $request->long,
            'pertemuan_ke' => $pertemuan,
            'status' => 'H'
        ]);

        // Notifikasi ke orang tua
        $ortu = OrangTua::where('siswa_id', $siswa->id)->first();
        $ortuPhone = $ortu->no_telepon;
        $jam = now()->format('H:i');
        $tanggal = now()->format('d-m-Y');
        $mapel = $absenqr->jadwal->mapel->nama ?? 'Pelajaran';
        $message = "📢 *Notifikasi Absensi*\n\n"
            . "Halo, *{$ortu->nama}*.\n\n"
            . "Anak Anda, *{$siswa->user->name}*, telah melakukan absensi pada:\n"
            . "📅 Tanggal: *{$tanggal}*\n"
            . "⏰ Jam: *{$jam}*\n"
            . "📚 Mata Pelajaran: *{$mapel}*\n\n"
            . "Terima kasih.";

        if ($ortuPhone) {
            $fonnteService->sendMessage($ortuPhone, $message);
        }

        return back()->with('success', 'Absen berhasil direkam.');
    }

    private function hitungJarak($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000; // in meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
