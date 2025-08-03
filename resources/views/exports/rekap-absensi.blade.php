<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Kelas</th>
            @foreach ($tanggalAbsen as $pertemuanKe => $tanggal)
                <th>Pertemuan ke-{{ $pertemuanKe }} ({{ $tanggal }})</th>
            @endforeach
            <th>H</th>
            <th>S</th>
            <th>I</th>
            <th>A</th>
            <th>Jumlah Pertemuan</th>
            <th>Total Kehadiran</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($absens as $siswa_id => $absenGroup)
            @php
                $siswa = $absenGroup->first()->Siswa;
                $hadirCount = $absenGroup->where('status', 'H')->count();
                $sakitCount = $absenGroup->where('status', 'S')->count();
                $izinCount = $absenGroup->where('status', 'I')->count();
                $alfaCount = $absenGroup->where('status', 'A')->count();
                $totalKehadiran = $hadirCount + $sakitCount + $izinCount + $alfaCount;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $siswa->user->name }}</td>
                <td>{{ $jadwal->kelas->nama }}</td>
                @foreach ($tanggalAbsen as $pertemuanKe => $tanggal)
                    @php
                        $status = $absenGroup->firstWhere('pertemuan_ke', $pertemuanKe)->status ?? '-';
                    @endphp
                    <td>{{ $status }}</td>
                @endforeach
                <td>{{ $hadirCount }}</td>
                <td>{{ $sakitCount }}</td>
                <td>{{ $izinCount }}</td>
                <td>{{ $alfaCount }}</td>
                <td>{{ $jumlahPertemuan }}</td>
                <td>{{ $totalKehadiran }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
