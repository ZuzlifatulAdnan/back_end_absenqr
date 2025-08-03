<?php

namespace App\Exports;

use App\Models\Absen;
use App\Models\Jadwal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapAbsensiExport implements FromView
{
    protected $jadwal_id;

    public function __construct($jadwal_id)
    {
        $this->jadwal_id = $jadwal_id;
    }

    public function view(): View
    {
        $absen = Absen::with('Siswa')->where('jadwal_id', $this->jadwal_id)->get();
        $jumlahPertemuan = $absen->max('pertemuan_ke');

        $tanggalAbsen = [];
        for ($i = 1; $i <= $jumlahPertemuan; $i++) {
            $pertemuan = $absen->where('pertemuan_ke', $i)->first();
            $tanggalAbsen[$i] = $pertemuan && $pertemuan->tanggal_absen
                ? \Carbon\Carbon::parse($pertemuan->tanggal_absen)->format('d-m-Y')
                : 'N/A';
        }

        $absens = $absen->groupBy('siswa_id');
        $jadwal = Jadwal::findOrFail($this->jadwal_id);

        return view('exports.rekap-absensi', compact('absens', 'jadwal', 'jumlahPertemuan', 'tanggalAbsen'));
    }
}
