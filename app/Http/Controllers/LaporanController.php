<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = date('m');
        $tahun = date('Y');

        if ($request->has('bulan') && $request->bulan != "" && $request->has('tahun') && $request->tahun) {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
        }

        // Set locale ke Indonesia
        Carbon::setLocale('id');

        return view('laporan.index', compact('bulan', 'tahun'));
    }

    public function getData($bulan, $tahun)
    {
        $no = 1;
        $data = array();
        $pendapatan = 0;
        $total_pendapatan = 0;

        $awal = Carbon::create($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $akhir = Carbon::create($tahun, $bulan, 1)->endOfMonth()->toDateString();

        while (strtotime($awal) <= strtotime($akhir)) {
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

            $penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->get();
            if ($penjualan->count() > 0) {
                foreach ($penjualan as $p) {
                    $nama_member = $p->member->nama ?? '';

                    foreach ($p->penjualanDetail as $detail) {
                        $harga_jual = $detail->harga_jual;
                        $jumlah = $detail->jumlah;
                        $diskon = $detail->diskon;

                        // Hitung penjualan setelah diskon
                        $penjualan_setelah_diskon = $harga_jual * $jumlah * (1 - $diskon / 100);
                        $total_pendapatan += $penjualan_setelah_diskon;

                        $row = array();
                        $row['tanggal'] = tanggal_indonesia($tanggal, false);
                        $row['nama'] = $nama_member;
                        $row['nama_produk'] = $detail->produk->nama_produk;
                        $row['harga_jual'] = format_uang($harga_jual);
                        $row['jumlah'] = $jumlah;
                        $row['diskon'] = $diskon . '%';
                        $row['penjualan'] = format_uang($penjualan_setelah_diskon);

                        $data[] = $row;
                    }
                }
            }
        }

        if ($total_pendapatan > 0) {
            $data[] = [
                'tanggal' => '',
                'nama' => '',
                'nama_produk' => '',
                'harga_jual' => '',
                'jumlah' => '',
                'diskon' => 'Total Pendapatan',
                'penjualan' => format_uang($total_pendapatan),
            ];
        }

        return $data;
    }

    public function data($bulan, $tahun)
    {
        $data = $this->getData($bulan, $tahun);

        return datatables()
            ->of($data)
            ->make(true);
    }

    public function exportPDF($bulan, $tahun)
    {
        Carbon::setLocale('id');
        $data = $this->getData($bulan, $tahun);
        
        // Dapatkan nama bulan dalam bahasa Indonesia
        $tanggal = Carbon::createFromDate($tahun, $bulan, 1);
        $bulanIndo = $tanggal->translatedFormat('F');
        
        // Generate PDF
        $pdf = PDF::loadView('laporan.pdf', compact('bulan', 'tahun', 'data'));
        $pdf->setPaper('a4', 'portrait');
        
        // Nama file dengan bulan dalam bahasa Indonesia
        $fileName = 'Laporan-penjualan-' . $bulanIndo . '-' . $tahun . '.pdf';
        
        return $pdf->stream($fileName);
    }
}
