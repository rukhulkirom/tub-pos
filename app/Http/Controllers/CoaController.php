<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coa;

class CoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('coa.index');
    }

    public function data()
    {
        $coa = Coa::orderBy('id_coa', 'asc')->get();

        return datatables()
            ->of($coa)
            ->addIndexColumn()
            ->addColumn('aksi', function ($coa) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'. route('coa.update', $coa->id_coa) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not implemented
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $coa = new Coa();
        $coa->kode_akun = $request->kode_akun;
        $coa->nama_akun = $request->nama_akun;
        $coa->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $coa = Coa::find($id);

        return response()->json($coa);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Not implemented
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $coa = Coa::find($id);
        $coa->kode_akun = $request->kode_akun;
        $coa->nama_akun = $request->nama_akun;
        $coa->update();

        return response()->json('Data berhasil diupdate', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Not implemented
    }
}
