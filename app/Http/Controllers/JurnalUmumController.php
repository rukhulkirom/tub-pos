<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

class JurnalUmumController extends Controller
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

        return view('jurnal_umum.index', compact('bulan', 'tahun'));
    }    

    public function getData($bulan, $tahun)
    {
        $coa = DB::table('coa')->first(); // Mengambil data pertama dari tabel coa
        if ($coa) {
            $kas = $coa->nama_akun;
            $refKas = $coa->kode_akun;
        } else {
            // Handle jika tidak ada data yang ditemukan
            $kas = 'Kas';
            $refKas = 111;
        }

        $coa = DB::table('coa')->skip(1)->take(1)->first(); // Mengambil data kedua dari tabel coa
        if ($coa) {
            $penjualan = $coa->nama_akun;
            $refPenjualan = $coa->kode_akun;
        } else {
            // Handle jika tidak ada data yang ditemukan
            $nama_akun = 'Penjualan';
            $refPenjualan = 411;
        }

        $data = array();
        $total_pendapatan = 0;

        // Set tanggal awal dan akhir berdasarkan bulan dan tahun
        $awal = Carbon::create($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $akhir = Carbon::create($tahun, $bulan, 1)->endOfMonth()->toDateString();

        while (strtotime($awal) <= strtotime($akhir)) {
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));
        
            $transaksis = Penjualan::whereDate('created_at', $tanggal)->get();
        
            foreach ($transaksis as $transaksi) {
                $row = array();
                $row['tanggal'] = tanggal_indonesia($tanggal, false);
                $row['akun'] = $kas;
                $row['ref'] = $refKas;
                $row['debit'] = format_uang($transaksi->bayar);
                $row['kredit'] = '';
        
                $data[] = $row;
        
                $row = array();
                $row['tanggal'] = '';
                $row['akun'] = $penjualan;
                $row['ref'] = $refPenjualan;
                $row['debit'] = '';
                $row['kredit'] = format_uang($transaksi->bayar);
        
                $data[] = $row;

                 // Tambahkan ke total pendapatan
                $total_pendapatan += $transaksi->bayar;
            }
        }        

        // Tambahkan total di akhir
        if ($total_pendapatan > 0) {
            $data[] = [
                'tanggal' => '',
                'akun' => 'Total',
                'ref' => '',
                'debit' => format_uang($total_pendapatan),
                'kredit' => format_uang($total_pendapatan),
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
        $pdf = PDF::loadView('jurnal_umum.pdf', compact('bulan', 'tahun', 'data'));
        $pdf->setPaper('a4', 'portrait');
        
        // Nama file dengan bulan dalam bahasa Indonesia
        $fileName = 'Jurnal-Umum-' . $bulanIndo . '-' . $tahun . '.pdf';
        
        return $pdf->stream($fileName);
    }
}
