<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;

class BukuBesarController extends Controller
{
    public function index(Request $request)
    {
        $bulan = date('m');
        $tahun = date('Y');

        if ($request->has('bulan') && $request->has('tahun')) {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
        }

        return view('buku_besar.index', compact('bulan', 'tahun'));
    }

    public function getData($bulan, $tahun, $filter = 'kas')
    {
        $coaKas = DB::table('coa')->where('kode_akun', 111)->first(); // Kas
        $coaPenjualan = DB::table('coa')->where('kode_akun', 411)->first(); // Penjualan

        $kas = $coaKas ? $coaKas->nama_akun : 'Kas';
        $refKas = $coaKas ? $coaKas->kode_akun : 111;

        $penjualan = $coaPenjualan ? $coaPenjualan->nama_akun : 'Penjualan';
        $refPenjualan = $coaPenjualan ? $coaPenjualan->kode_akun : 411;

        $data = [];
        $total_pendapatan = 0;
        $saldo_berjalan = 0;

        $awal = Carbon::create($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $akhir = Carbon::create($tahun, $bulan, 1)->endOfMonth()->toDateString();

        // Mendapatkan saldo akhir bulan kemarin
        $saldoAkhirBulanKemarin = DB::table('penjualan')
            ->whereDate('created_at', '<', $awal)
            ->orderBy('created_at', 'desc')
            ->value('saldo_akhir');

        // Menambahkan saldo akhir bulan kemarin ke saldo awal
        $total_pendapatan += $saldoAkhirBulanKemarin ?? 0;
        $saldo_berjalan += $saldoAkhirBulanKemarin ?? 0;

        // Menambahkan saldo awal ke data
        if ($saldoAkhirBulanKemarin !== null) {
            if ($filter == 'kas') {
                $data[] = [
                    'tanggal' => '',
                    'akun' => 'Saldo Awal',
                    'ref' => '',
                    'debit' => '',
                    'kredit' => '',
                    'saldo_debit' => format_uang($saldoAkhirBulanKemarin),
                    'saldo_kredit' => '',
                ];
            } elseif ($filter == 'penjualan') {
                $data[] = [
                    'tanggal' => '',
                    'akun' => 'Saldo Awal',
                    'ref' => '',
                    'debit' => '',
                    'kredit' => '',
                    'saldo_debit' => '',
                    'saldo_kredit' => format_uang($saldoAkhirBulanKemarin),
                ];
            } else {
                $data[] = [
                    'tanggal' => '',
                    'akun' => 'Total',
                    'ref' => '',
                    'debit' => format_uang($saldoAkhirBulanKemarin),
                    'kredit' => format_uang($saldoAkhirBulanKemarin),
                    'saldo_debit' => format_uang($saldoAkhirBulanKemarin),
                    'saldo_kredit' => format_uang($saldoAkhirBulanKemarin),
                ];
            }
        }

        // Mengambil transaksi per transaksi
        $transaksis = Penjualan::whereBetween('created_at', [$awal, $akhir])->get();

        foreach ($transaksis as $transaksi) {
            $tanggal = $transaksi->created_at->toDateString();
            $saldo_berjalan += $transaksi->bayar;
            $total_pendapatan += $transaksi->bayar;

            $ref = 'JU-' . $transaksi->id_penjualan;

            if ($filter == 'kas') {
                $data[] = [
                    'tanggal' => tanggal_indonesia($tanggal, false),
                    'akun' => $kas,
                    'ref' => $ref,
                    'debit' => format_uang($transaksi->bayar),
                    'kredit' => '',
                    'saldo_debit' => format_uang($saldo_berjalan),
                    'saldo_kredit' => '',
                ];
            } elseif ($filter == 'penjualan') {
                $data[] = [
                    'tanggal' => tanggal_indonesia($tanggal, false),
                    'akun' => $penjualan,
                    'ref' => $ref,
                    'debit' => '',
                    'kredit' => format_uang($transaksi->bayar),
                    'saldo_debit' => '',
                    'saldo_kredit' => format_uang($saldo_berjalan),
                ];
            } else {
                $data[] = [
                    'tanggal' => tanggal_indonesia($tanggal, false),
                    'akun' => $kas,
                    'ref' => $ref,
                    'debit' => format_uang($transaksi->bayar),
                    'kredit' => '',
                    'saldo_debit' => format_uang($saldo_berjalan),
                    'saldo_kredit' => '',
                ];

                $data[] = [
                    'tanggal' => '',
                    'akun' => $penjualan,
                    'ref' => $ref,
                    'debit' => '',
                    'kredit' => format_uang($transaksi->bayar),
                    'saldo_debit' => '',
                    'saldo_kredit' => format_uang($saldo_berjalan),
                ];
            }
        }

        // Tambahkan total di akhir
        if ($filter == 'kas') {
            if ($total_pendapatan > 0) {
                $data[] = [
                    'tanggal' => '',
                    'akun' => 'Saldo Akhir',
                    'ref' => '',
                    'debit' => '',
                    'kredit' => '',
                    'saldo_debit' => format_uang($total_pendapatan),
                    'saldo_kredit' => '',
                ];
            }
        } elseif ($filter == 'penjualan') {
            if ($total_pendapatan > 0) {
                $data[] = [
                    'tanggal' => '',
                    'akun' => 'Saldo Akhir',
                    'ref' => '',
                    'debit' => '',
                    'kredit' => '',
                    'saldo_debit' => '',
                    'saldo_kredit' => format_uang($total_pendapatan),
                ];
            }
        } else {
            if ($total_pendapatan > 0) {
                $data[] = [
                    'tanggal' => '',
                    'akun' => 'Saldo Akhir',
                    'ref' => '',
                    'debit' => format_uang($total_pendapatan),
                    'kredit' => format_uang($total_pendapatan),
                    'saldo_debit' => format_uang($total_pendapatan),
                    'saldo_kredit' => format_uang($total_pendapatan),
                ];
            }
        }

        // Simpan saldo akhir bulan ini ke tabel penjualan
        DB::table('penjualan')->whereBetween('created_at', [$awal, $akhir])->update(['saldo_akhir' => $saldo_berjalan]);

        return $data;
    }

    public function data($bulan, $tahun, Request $request)
    {
        $filter = $request->input('filter');
        
        if ($filter == 'kas') {
            $data = $this->getData($bulan, $tahun, 'kas');
        } elseif ($filter == 'penjualan') {
            $data = $this->getData($bulan, $tahun, 'penjualan');
        } else {
            $data = $this->getData($bulan, $tahun); // Jika tidak ada filter, ambil semua data
        }

        return datatables()
            ->of($data)
            ->make(true);
    }

    public function exportPDF($bulan, $tahun, Request $request)
    {
        Carbon::setLocale('id');
        $filter = $request->input('filter', 'kas');

        $data = $this->getData($bulan, $tahun, $filter);
        
        // Dapatkan nama bulan dalam bahasa Indonesia
        $namaBulan = Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        // Buat nama file dengan bulan dalam bahasa Indonesia
        $fileName = 'Buku-Besar-' . ucfirst($filter) . '-' . $namaBulan . '-' . $tahun . '.pdf';

        $pdf = PDF::loadView('buku_besar.pdf', compact('bulan', 'tahun', 'data', 'filter'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream($fileName);
    }
}
