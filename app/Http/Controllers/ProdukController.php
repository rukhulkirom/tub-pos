<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Produk;
use PDF;
use DNS1D;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = Kategori::all()->pluck('nama_kategori', 'id_kategori');

        return view('produk.index', compact('kategori'));
    }

    public function data()
    {
        $produk = Produk::leftJoin('kategori', 'kategori.id_kategori', 'produk.id_kategori')
            ->select('produk.*', 'nama_kategori')
            // ->where('barcode_printed', false) // Hanya yang belum dicetak barcode
            ->get();

            return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('select_all', function ($produk) {
                return '
                    <input type="checkbox" name="id_produk[]" value="'. $produk->id_produk .'">
                ';
            })
            ->addColumn('kode_produk', function ($produk) {
                return '<span class="label label-success">'. $produk->kode_produk .'</span>';
            })
            ->addColumn('harga_jual', function ($produk) {
                return format_uang($produk->harga_jual);
            })
            ->addColumn('stok', function ($produk) {
                return ($produk->stok);
            })
            ->addColumn('barcode_printed', function ($produk) {
                if ($produk->barcode_printed) {
                    return '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($produk->kode_produk, 'C39') . '" alt="' . $produk->kode_produk . '" width="150" height="60">';
                } else {
                    return ''; // Kosongkan jika belum dicetak
                }
            })
            ->addColumn('aksi', function ($produk) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('produk.update', $produk->id_produk) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_produk', 'select_all', 'barcode_printed'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'harga_jual' => 'required|numeric|min:0',
            'diskon' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:1',
        ]);

        $kategori = Kategori::find($request->id_kategori);
        $kodeKategori = strtoupper(substr($kategori->nama_kategori, 0, 3));

        $lastProduk = Produk::where('id_kategori', $request->id_kategori)
            ->orderBy('kode_produk', 'desc')
            ->first();

        if ($lastProduk) {
            $lastKode = (int) substr($lastProduk->kode_produk, -4);
        } else {
            $lastKode = 0;
        }
        $kodeProduk = $lastKode + 1;

        $produk = new Produk();
        $produk->kode_produk = 'TUB-' . $kodeKategori . '-' . str_pad($kodeProduk, 4, '0', STR_PAD_LEFT);
        $produk->nama_produk = $request->nama_produk;
        $produk->id_kategori = $request->id_kategori;
        $produk->harga_jual = $request->harga_jual;
        $produk->diskon = $request->diskon;
        $produk->stok = $request->stok;
        $produk->barcode_printed = false; // Tandai belum dicetak barcode
        $produk->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk::find($id);

        return response()->json($produk);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'harga_jual' => 'required|numeric|min:0',
            'diskon' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:1', // Validasi stok tidak boleh 0 atau kurang
        ]);

        // Temukan produk berdasarkan id
        $produk = Produk::find($id);
        
        // Perbarui data produk
        $produk->update($request->all());

        return response()->json('Data berhasil diupdate', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function cetakBarcode(Request $request)
    {
        $dataproduk = Produk::with('kategori')
            ->whereIn('id_produk', $request->id_produk)
            ->where('barcode_printed', false) // Hanya produk yang belum dicetak barcode
            ->get();

        foreach ($dataproduk as $produk) {
            // Melakukan penandaan barcode sudah dicetak
            $produk->barcode_printed = true;
            $produk->save();
        }

        $no  = 1;
        $pdf = PDF::loadView('produk.barcode', compact('dataproduk', 'no'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('produk.pdf');
    }
}