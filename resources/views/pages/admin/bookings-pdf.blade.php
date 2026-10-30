<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Booking — Excellent Barbershop</title>
    <style>
        @page {
            margin: 20px 25px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #211f1c;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #a93822;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .brand-title {
            font-size: 20px;
            font-weight: bold;
            color: #211f1c;
            letter-spacing: -0.5px;
            margin: 0;
        }
        .brand-title span {
            color: #a93822;
        }
        .report-title {
            font-size: 13px;
            font-weight: bold;
            color: #6d655d;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 4px 0 0 0;
        }
        .meta-text {
            text-align: right;
            font-size: 10px;
            color: #6d655d;
        }
        .filter-info {
            background-color: #f7f5f0;
            border: 1px solid #e7e2da;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 14px;
            font-size: 10px;
        }
        .filter-info span {
            margin-right: 15px;
            color: #4a443d;
        }
        .filter-info strong {
            color: #211f1c;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .summary-table td {
            width: 20%;
            padding: 8px 10px;
            background: #faf8f5;
            border: 1px solid #e7e2da;
            text-align: center;
        }
        .summary-label {
            font-size: 9px;
            text-transform: uppercase;
            color: #6d655d;
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
        }
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #211f1c;
        }
        .summary-value.highlight {
            color: #a93822;
        }
        .summary-value.confirmed {
            color: #1b7340;
        }
        .summary-value.pending {
            color: #b7791f;
        }
        .summary-value.rejected {
            color: #c53030;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th {
            background-color: #211f1c;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 7px 6px;
            text-align: left;
            border: 1px solid #211f1c;
        }
        .data-table td {
            padding: 6px;
            border: 1px solid #ded8cf;
            font-size: 10px;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) td {
            background-color: #faf8f5;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 3px;
            letter-spacing: 0.5px;
        }
        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }
        .badge-confirmed {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        .badge-rejected {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .rejection-reason {
            color: #991b1b;
            font-size: 9px;
            margin-top: 3px;
            font-style: italic;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .footer-note {
            font-size: 9px;
            color: #8d8379;
        }
        .sign-box {
            text-align: right;
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .empty-cell {
            text-align: center;
            padding: 20px;
            color: #6d655d;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td>
                    <h1 class="brand-title">EXCELLENT <span>BARBERSHOP</span></h1>
                    <div class="report-title">Laporan Rekap Data Reservasi & Booking</div>
                </td>
                <td class="meta-text">
                    <strong>Dicetak:</strong> {{ $generatedAt->format('d/m/Y H:i') }} WIB<br>
                    <strong>Sistem:</strong> Excellent Barbershop Studio
                </td>
            </tr>
        </table>
    </div>

    <div class="filter-info">
        <span><strong>Periode:</strong>
            @if(!empty($filters['start_date']) && !empty($filters['end_date']))
                {{ \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') }}
            @elseif(!empty($filters['start_date']))
                Mulai {{ \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') }}
            @elseif(!empty($filters['end_date']))
                Sampai {{ \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') }}
            @else
                Semua Tanggal
            @endif
        </span>
        <span><strong>Status:</strong> {{ !empty($filters['status']) ? ucfirst($filters['status']) : 'Semua Status' }}</span>
        <span><strong>Barber:</strong>
            @if(!empty($filters['barber_id']) && $bookings->isNotEmpty())
                {{ $bookings->first()->barber->name ?? 'Barber #'.$filters['barber_id'] }}
            @else
                Semua Barber
            @endif
        </span>
    </div>

    <table class="summary-table">
        <tr>
            <td>
                <span class="summary-label">Total Booking</span>
                <span class="summary-value">{{ $summary['total'] }}</span>
            </td>
            <td>
                <span class="summary-label">Dikonfirmasi</span>
                <span class="summary-value confirmed">{{ $summary['confirmed'] }}</span>
            </td>
            <td>
                <span class="summary-label">Menunggu</span>
                <span class="summary-value pending">{{ $summary['pending'] }}</span>
            </td>
            <td>
                <span class="summary-label">Ditolak</span>
                <span class="summary-value rejected">{{ $summary['rejected'] }}</span>
            </td>
            <td>
                <span class="summary-label">Total Omset</span>
                <span class="summary-value highlight">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th style="width: 90px;">Kode Booking</th>
                <th style="width: 90px;">Jadwal</th>
                <th style="width: 110px;">Pelanggan</th>
                <th style="width: 80px;">No. HP</th>
                <th style="width: 90px;">Barber</th>
                <th style="width: 100px;">Layanan</th>
                <th style="width: 75px;" class="text-right">Harga</th>
                <th style="width: 75px;" class="text-center">Status</th>
                <th>Catatan / Alasan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $index => $booking)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $booking->booking_code }}</strong></td>
                    <td>
                        {{ $booking->booking_date->format('d/m/Y') }}<br>
                        <small style="color: #6d655d;">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}</small>
                    </td>
                    <td>{{ $booking->customer->name ?? '-' }}</td>
                    <td>{{ $booking->customer->phone ?? '-' }}</td>
                    <td>{{ $booking->barber->name ?? '-' }}</td>
                    <td>{{ $booking->service->name ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($booking->service->price ?? 0, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($booking->status === 'confirmed')
                            <span class="badge badge-confirmed">Confirmed</span>
                        @elseif($booking->status === 'rejected')
                            <span class="badge badge-rejected">Rejected</span>
                        @else
                            <span class="badge badge-pending">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($booking->notes)
                            <div><strong>Catatan:</strong> {{ $booking->notes }}</div>
                        @endif
                        @if($booking->status === 'rejected' && $booking->rejection_reason)
                            <div class="rejection-reason"><strong>Alasan Tolak:</strong> {{ $booking->rejection_reason }}</div>
                        @endif
                        @if(!$booking->notes && !$booking->rejection_reason)
                            <span style="color: #8d8379;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="empty-cell">
                        Tidak ada data booking yang sesuai dengan kriteria filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-table">
        <tr>
            <td class="footer-note" style="width: 60%; vertical-align: bottom;">
                * Laporan ini dibuat secara otomatis melalui sistem reservasi Excellent Barbershop.<br>
                * Total omset hanya memperhitungkan transaksi dengan status <em>Confirmed</em>.
            </td>
            <td class="sign-box" style="width: 40%; vertical-align: bottom;">
                <div>Dicetak pada {{ $generatedAt->format('d F Y') }}</div>
                <div style="margin-top: 45px; font-weight: bold; border-top: 1px solid #ded8cf; display: inline-block; padding-top: 4px; min-width: 140px; text-align: center;">
                    Admin Barbershop
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
