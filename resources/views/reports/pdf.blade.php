<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Smart Security</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; font-size: 18px; }
        h2 { font-size: 14px; margin-top: 20px; border-bottom: 2px solid #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 10px; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-warning { background-color: #ffc107; color: black; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN SMART SECURITY SYSTEM</h1>
        <p><strong>PT Pupuk Kalimantan Timur - Departemen Keamanan</strong></p>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
    </div>

    <h2>1. LOG SHEET CCTV</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Shift</th>
                <th>Petugas</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Kejadian</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cctvLogs as $index => $log)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $log->log_time->format('d/m/Y H:i') }}</td>
                <td>{{ $log->shift }}</td>
                <td>{{ $log->user->full_name }}</td>
                <td>{{ $log->camera_location }}</td>
                <td>
                    @if($log->status == 'Aman')
                        <span class="badge badge-success">Aman</span>
                    @else
                        <span class="badge badge-danger">Tidak Aman</span>
                    @endif
                </td>
                <td>{{ \Str::limit($log->incident_description, 60) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h2>2. PENGECEKAN INVENTARIS</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Shift</th>
                <th>Petugas</th>
                <th>Lokasi</th>
                <th>Jumlah Item</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventoryChecks as $index => $check)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $check->check_date->format('d/m/Y') }}</td>
                <td>{{ $check->shift }}</td>
                <td>{{ $check->user->full_name }}</td>
                <td>{{ $check->pos_location }}</td>
                <td>{{ $check->details->count() }} item</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h2>3. TIKET PERBAIKAN (MAINTENANCE)</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Tiket</th>
                <th>Pelapor</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Teknisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $index => $ticket)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $ticket->ticket_code }}</td>
                <td>{{ $ticket->reporter->full_name }}</td>
                <td>{{ \Str::limit($ticket->issue_description, 50) }}</td>
                <td>
                    @if($ticket->status == 'Open')
                        <span class="badge badge-danger">Open</span>
                    @elseif($ticket->status == 'In Progress')
                        <span class="badge badge-warning">In Progress</span>
                    @else
                        <span class="badge badge-success">{{ $ticket->status }}</span>
                    @endif
                </td>
                <td>{{ $ticket->technician ? $ticket->technician->full_name : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 40px; text-align: right;">
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }}</p>
    </div>
</body>
</html>