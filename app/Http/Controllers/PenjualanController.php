<?php

namespace App\Http\Controllers;

use App\Penjualan;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\DataTables;
use App\Barang;
use App\KategoriBarang;

class PenjualanController extends Controller
{
    public function json()
    {
        $jual = Penjualan::all();
        return Datatables::of($jual)
        ->addColumn('jual', function($jual){
            return $jual->barangjual->Merk;
        })
        ->addColumn('kategori', function($jual){
            return $jual->barangkategori->Nama_Kategori;
        })
        ->addColumn('subbar', function($jual){
            return $jual->sub->Nama_Kategori;
        })
        ->addColumn('formatharga', function($jual){
            return number_format($jual->Total_Bayar,2,',','.');
        })
        ->addColumn('action', function($jual){
            return '<a href="#" class="btn btn-xs btn-primary edit" data-id="'.$jual->id.'">
            <i class="glyphicon glyphicon-edit"></i> Edit</a>&nbsp;
            <a href="#" class="btn btn-xs btn-danger delete" id="'.$jual->id.'">
            <i class="glyphicon glyphicon-remove"></i> Delete</a>';
        })
        ->rawColumns(['action','jual','formatharga','kategori','sub'])->make(true);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $barang = Barang::all();
        $barkategori = KategoriBarang::where('parent_id','=',null)->get();
        return view('penjual.index', compact('barang','barkategori'));
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
        $barang = Barang::where('id', $request->Barang_id)->first();
        $stok = $barang->Stok;
        $this->validate($request, [
            'Kode_Penjualan' => 'required|min:5|max:10',
            'Tanggal_Jual' => 'required',
            'Nama_Pelanggan' => 'required',
            'Barang_id' => 'required',
            'kat_id' => 'required',
            'Jumlah' => "required|numeric|min:1|max:$stok",
        ],[
            'Kode_Penjualan.required' => 'Kode Penjualan Tidak Boleh Kosong',
            'Kode_Penjualan.min' => 'minimal 5 karater',
            'Kode_Penjualan.max' => 'maximal 10 karater',
            'Tanggal_Jual.required' => 'Harus Diisi',
            'Nama_Pelanggan.required' => 'Nama Pelanggan Tidak Boleh Kosong',
            'Barang_id.required' => 'Harga Harus Diisi',
            'kat_id.required' => 'Harus Diisi',
            'Jumlah.required' => 'Jumlah Harus Diisi',
            'Jumlah.max' => 'tidak boleh melebihi stok, Stok Barang Saat Ini = '.$stok,
            'Jumlah.min' => 'tidak boleh kurang dari 1',
        ]);
        $data = new Penjualan;
        $data->Kode_Penjualan = $request->Kode_Penjualan;
        $data->Tanggal_Jual = $request->Tanggal_Jual;
        $data->Nama_Pelanggan = $request->Nama_Pelanggan;
        $data->Barang_id = $request->Barang_id;
        $data->kat_id = $request->kat_id;
        $data->sub_id = $request->sub_id;
        $data->Jumlah = $request->Jumlah;

        $baru = Barang::where('id', $data->Barang_id)->first();

        $data->Total_Bayar = $data->Jumlah*$baru->Harga_Satuan;
        $data->save();

        $baru->Stok = $baru->Stok - $data->Jumlah;
        $baru->save();
        return response()->json(['success'=>true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function show(Penjualan $penjualan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        return $penjualan;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'Kode_Penjualan' => 'required',
            'Tanggal_Jual' => 'required',
            'Nama_Pelanggan' => 'required',
            'Barang_id' => 'required',
            'kat_id' => 'required',
            'Jumlah' => 'required|numeric|not_in:0',
        ],[
            'Kode_Penjualan.required' => 'Kode Penjualan Tidak Boleh Kosong',
            'Tanggal_Jual.required' => 'Harus Diisi',
            'Nama_Pelanggan.required' => 'Nama Pelanggan Tidak Boleh Kosong',
            'Barang_id.required' => 'Harga Harus Diisi',
            'kat_id.required' => 'Harus Diisi',
            'Jumlah.required' => 'Jumlah Harus Diisi',
            'Jumlah.numeric' => 'inputan Harus berupa angka',
            'Jumlah.not_in' => 'tidak bisa menginput',
        ]);
        $data = Penjualan::find($id);
        $data->Kode_Penjualan = $request->Kode_Penjualan;
        $data->Tanggal_Jual = $request->Tanggal_Jual;
        $data->Nama_Pelanggan = $request->Nama_Pelanggan;
        $data->Barang_id = $request->Barang_id;
        $data->kat_id = $request->kat_id;

        $baru = Barang::find($id);
        if ($request->Jumlah <= $baru->Stok) {
            $anyar = Penjualan::find($id);
            $anyar->Jumlah = $anyar->Jumlah - $request->Jumlah;

            $new = Barang::where('id', $data->Barang_id)->first();
            $new->Stok = $new->Stok + $anyar->Jumlah;
            $anyar->save();
            $new->save();
            $data->Jumlah = $request->Jumlah;

            $data->Total_Bayar = $data->Jumlah*$baru->Harga_Satuan;
            $data->save();
            return response()->json(['success'=>true,'message'=>'berhasil update']);
        }
        elseif ($request->Jumlah > $baru->Stok)  {

            $barangstok = Barang::where('id', $data->Barang_id)->first();
            $barangstok->Stok = ($barangstok->Stok + $data->Jumlah) - $request->Jumlah;
            $barangstok->save();
            $data->Jumlah = $request->Jumlah;

            $coba = Penjualan::find($id);
            $coba->Jumlah = $coba->Jumlah - $request->Jumlah;
            $coba->save();

            $data->Total_Bayar = $data->Jumlah*$baru->Harga_Satuan;
            $data->save();

            return response()->json(['success'=>true,'message'=>'terpenuhi']);
        }
        return response()->json(['error'=>true, 'pesan'=>'tidak terpenuhi']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penjualan $penjualan)
    {
        //
    }
    public function removedata(Request $request)
    {
        $penjualan = Penjualan::find($request->input('id'));
        if($penjualan->delete())
        {
            echo 'Data Deleted';
        }
    }
}
