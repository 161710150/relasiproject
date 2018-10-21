<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Suplier;
use App\KategoriBarang;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\DataTables;
use DB;

class BarangController extends Controller
{
    public function json()
    {
        $barang = Barang::all();
        return Datatables::of($barang)
        ->addColumn('kategori', function($barang){
            return $barang->kategoribarang->Nama_Kategori;
        })
        ->addColumn('parent', function($barang){
            return $barang->subbarang->Nama_Kategori;
        })
        ->addColumn('supliername', function($barang){
            return $barang->suplier->Nama;
        })
        ->addColumn('formatharga', function($barang){
            return number_format($barang->Harga_Satuan,2,',','.');
        })
        ->addColumn('action', function($barang){
            return '<a href="#" class="btn btn-xs btn-primary edit" data-id="'.$barang->id.'">
            <i class="glyphicon glyphicon-edit"></i> Edit</a>&nbsp;
            <a href="#" class="btn btn-xs btn-danger delete" id="'.$barang->id.'">
            <i class="glyphicon glyphicon-remove"></i> Delete</a>';

            })
        ->rawColumns(['action','supliername','formatharga','kategori','parent'])->make(true);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suplier = Suplier::all();
        $barangkategori = KategoriBarang::where('parent_id','=',null)->get();
        return view('barang.index', compact('suplier','barangkategori'));
    }

    public function myformAjax($id)
    {
        $sub = DB::table("kategori_barangs")
                    ->where("parent_id",$id)
                    ->pluck("Nama_Kategori","id");
        return json_encode($sub);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'suplier_id' => 'required',
            'kat_id' => 'required',
            'id_parent' => 'required',
            'Merk' => 'required|unique:barangs,Merk',
            'Harga_Satuan' => 'required',
            'Stok' => 'required|not_in:0',
        ],[
            'suplier_id.required' => 'suplier Tidak Boleh Kosong',
            'kat_id.required' => 'Kategori tidak boleh Kosong',
            'id_parent.required' => 'Nama Barang Harus Diisi',
            'Merk.required' => 'Merk Tidak Boleh Kosong',
            'Harga_Satuan.required' => 'Harga Harus Diisi',
            'Stok.required' => 'Stok Harus Diisi',
            'Stok.not_in' => 'Tidak Dapat Menginput',
        ]);
        $data = new Barang;
        $data->suplier_id = $request->suplier_id;
        $data->kat_id = $request->kat_id;
        $data->id_parent = $request->id_parent;
        $data->Merk = $request->Merk;
        $data->Harga_Satuan = $request->Harga_Satuan;
        $data->Stok = $request->Stok;
        $data->save();
        return response()->json(['success'=>true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function show(Barang $barang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return $barang;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'suplier_id'=>'required',
            'kat_id' => 'required',
            'id_parent' => 'required',
            'Merk'=>'required',
            'Harga_Satuan' => 'required',
            'Stok' => 'required|not_in:0',
        ],[
            'suplier_id.required' => 'suplier_id Tidak Boleh Kosong',
            'kat_id.required' => 'Harus Diisi',
            'id_parent.required' => 'Harus Diisi',
            'Merk.required' => 'Tidak Boleh Kosong',
            'Harga_Satuan.required' => 'Tidak Boleh Kosong',
            'Stok.required' => 'Tidak Boleh Kosong',
            'Stok.not_in' => 'Minimal 1',
        ]);
        $barang = Barang::findOrFail($id);
        $barang->suplier_id = $request->suplier_id;
        $barang->kat_id = $request->kat_id;
        $barang->id_parent = $request->id_parent;
        $barang->Merk = $request->Merk;
        $barang->Harga_Satuan = $request->Harga_Satuan;
        $barang->Stok = $request->Stok;
        $success = $barang->save();
        if ($success){
            return response()->json([
                'success'=>true,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Barang $barang)
    {
        //
    }

    public function removedata(Request $request)
    {
        $barang = Barang::find($request->input('id'));
        if($barang->delete())
        {
            echo 'Data Deleted';
        }
    }
}
