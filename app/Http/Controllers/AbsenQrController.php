<?php

namespace App\Http\Controllers;

use App\Models\Absen_Qr;
use App\Models\Jadwal;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class AbsenQrController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'absen';

        $query = Absen_Qr::with('jadwal.mapel', 'jadwal.kelas');

        if ($request->filled('nama')) {
            $query->whereHas('jadwal.guru', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->nama . '%');
            });
        }

        $absenqr = $query->latest()->paginate(10);

        return view('pages.absenqr.index', compact('absenqr', 'type_menu'));
    }


    public function create()
    {
        $type_menu = 'absen';
        // Optimasi query, hanya ambil kolom yang dibutuhkan
        $jadwal = Jadwal::all(); // Ganti 'nama_mapel' sesuai nama kolom Anda

        return view('pages.absenqr.create', compact('type_menu', 'jadwal'));
    }

    protected function generateQRCodeToken()
    {
        do {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $code = substr(str_shuffle($characters), 0, 6);
        } while (Absen_Qr::where('token_qr', $code)->exists());

        return $code;
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'tanggal_absen' => 'required|date',
            'expired_at' => 'required|date|after:tanggal_absen',
        ]);

        $absenqr = Absen_Qr::create([
            'jadwal_id' => $request->jadwal_id,
            'tanggal_absen' => $request->tanggal_absen,
            'token_qr' => $this->generateQRCodeToken(),
            'expired_at' => $request->expired_at,
        ]);

        return redirect()->route('absenqr.index')->with('success', 'QR Token Berhasil di Buat untuk Tanggal Absen ' . $absenqr->tanggal_absen);
    }
    public function edit($id)
    {
        $type_menu = 'absen';
        $absenqr = Absen_Qr::with('jadwal.mapel', 'jadwal.kelas')->findOrFail($id);
        $jadwal = Jadwal::all();

        return view('pages.absenqr.edit', compact('absenqr', 'type_menu', 'jadwal'));

    }
    public function update(Request $request, Absen_Qr $absen_qr)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwals,id',
            'tanggal_absen' => 'required|date',
            'expired_at' => 'required|date|after:tanggal_absen',
        ]);

        $absen_qr->update([
            'jadwal_id' => $request->jadwal_id,
            'tanggal_absen' => $request->tanggal_absen,
            'expired_at' => $request->expired_at,
        ]);

        return redirect()->route('qr.index')->with('success', 'QR Token Berhasil di Perbarui untuk Tanggal Absen ' . $absen_qr->tanggal_absen);
    }
    private function generateQrCode($data)
    {
        $qrCode = QrCode::create($data)
            ->setSize(200)
            ->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return base64_encode($result->getString());
    }
    public function show($id)
    {
        $type_menu = 'absen';
        // 1. Cari data berdasarkan ID, jika tidak ada akan error 404
        $absenqr = Absen_Qr::with('jadwal.mapel', 'jadwal.kelas')->findOrFail($id);

        // 2. Kirim data tersebut ke file view
        return view('pages.absenqr.show', compact('absenqr', 'type_menu'));
    }
    public function downloadPDF($id)
    {
        $absenqr = Absen_Qr::findOrFail($id);

        // Generate QR codes as base64 images
        $qrCode = $this->generateQrCode($absenqr->token_qr);

        $data = [
            'absenqr' => $absenqr,
            'qrCode' => $qrCode,
        ];

        $pdf = Pdf::loadView('pages.pdf.qr_absen', $data);

        return $pdf->download('qr_absen_' . $absenqr->tanggal_absen . '.pdf');
    }
    public function view(Request $request, $id)
    {
        $type_menu = 'absen';

        $query = Absen_Qr::with('jadwal.mapel', 'jadwal.kelas')->where('jadwal_id', $id);

        if ($request->filled('nama')) {
            $query->whereHas('jadwal.guru', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->nama . '%');
            });
        }

        $absenqr = $query->latest()->paginate(10);

        return view('pages.absenqr.view', compact('absenqr', 'type_menu'));
    }
    public function createAdd($id)
    {
        $type_menu = 'absen';
        $jadwal = Jadwal::findOrFail($id);

        return view('pages.absenqr.add', compact('type_menu', 'jadwal'));
    }

    public function storeAdd(Request $request, $id)
    {
        $request->validate([
            'tanggal_absen' => 'required|date',
            'expired_at' => 'required|date|after:tanggal_absen',
        ]);

        $jadwal = Jadwal::findOrFail($id);

        $absenqr = Absen_Qr::create([
            'jadwal_id' => $jadwal->id,
            'tanggal_absen' => $request->tanggal_absen,
            'token_qr' => $this->generateQRCodeToken(),
            'expired_at' => $request->expired_at,
        ]);

        return redirect()->route('qr.view', ['id' => $jadwal->id])
            ->with('success', 'QR Token Berhasil di Buat untuk Tanggal Absen ' . $absenqr->tanggal_absen);

    }

    public function ubah($id)
    {
        $type_menu = 'absen';

        $absenqr = Absen_Qr::with('jadwal.mapel', 'jadwal.kelas')->findOrFail($id);

        return view('pages.absenqr.ubah', compact('absenqr', 'type_menu'));
    }

    public function updateUbah(Request $request, Absen_Qr $absen_qr)
    {
        $request->validate([
            'tanggal_absen' => 'required|date',
            'expired_at' => 'required|date|after:tanggal_absen',
            'jadwal_id' => 'required|exists:jadwals,id',
        ]);

        $absen_qr->update([
            'jadwal_id' => $request->jadwal_id,
            'tanggal_absen' => $request->tanggal_absen,
            'expired_at' => $request->expired_at,
        ]);

        return redirect()->route('qr.view', ['id' => $request->jadwal_id])
            ->with('success', 'QR Token Berhasil diperbarui untuk Tanggal Absen ' . $absen_qr->tanggal_absen);
    }

}
