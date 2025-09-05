<?php

namespace App\Exports;

use App\Models\Pemesanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class PemesananExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Pemesanan::with(['penumpang', 'rute.transportasi', 'petugas'])
            ->where('status', 'Sudah Bayar')
            ->get()
            ->map(function ($p) {
                return [
                    'KodeBooking'     => $p->kode,
                    'NomorKursi'      => str_replace(['["', '"]', '","'], ['', '', ', '], $p->kursi),
                    'NamaPemesan'     => $p->penumpang->name ?? '-',
                    'Email'           => $p->penumpang->email ?? '-', 
                    'NoTelp'          => $p->penumpang->username ?? '-',
                    'TicketPrice'     => 'Rp ' . number_format($p->rute->harga, 0, ',', '.'),
                    'Transportasi'    => $p->rute->transportasi->name ?? '-',
                    'TotalBayar'      => 'Rp ' . number_format($p->total, 0, ',', '.'),
                    'StatusPembayaran'=> $p->status_pembayaran,
                    'NamaVerifikator' => $p->petugas->name ?? '-',
                    'TanggalPemesanan'=> $p->waktu,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Booking',
            'Nomor Kursi',
            'Nama Pemesan',
            'Email',
            'No Telp',
            'Harga Tiket',
            'Transportasi',
            'Total Bayar',
            'Status Pembayaran',
            'Nama Verifikator',
            'Tanggal Pemesanan',
        ];
    }
}
