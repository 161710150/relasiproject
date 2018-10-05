<?php

namespace App\Http\Controllers;

use App\Penjualan;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\DataTables;
use App\Barang;

class PenjualanController extends Controller
{
    public function json()
    {
        $jual = Penjualan::all();
        return Datatables::of($jual)
        ->addColumn('jual', function($jual){
            return $jual->barangjual->Nama_Barang;
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
        ->rawColumns(['action','jual','formatharga'])->make(true);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $barang = Barang::all();
        return view('penjual.index', compact('barang'));
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
            'Kode_Penjualan' => 'required',
            'Tanggal_Jual' => 'required',
            'Nama_Pelanggan' => 'required',
            'Barang_id' => 'required',
            'Jumlah' => 'required',
            // 'Total_Bayar' => 'required',
        ],[
            'Kode_Penjualan.required' => 'Kode Penjualan Tidak Boleh Kosong',
            'Tanggal_Jual.required' => 'Harus Diisi',
            'Nama_Pelanggan.required' => 'Nama Pelanggan Tidak Boleh Kosong',
            'Barang_id.required' => 'Harga Harus Diisi',
            'Jumlah.required' => 'Jumlah Harus Diisi',
            // 'Total_Bayar.required' => 'Harus Diisi',
        ]);
        $data = new Penjualan;
        $data->Kode_Penjualan = $request->Kode_Penjualan;
        $data->Tanggal_Jual = $request->Tanggal_Jual;
        $data->Nama_Pelanggan = $request->Nama_Pelanggan;
        $data->Barang_id = $request->Barang_id;
        $data->Jumlah = $request->Jumlah;
        $total = $request->Jumlah;
        $baru = Barang::where('id', $data->Barang_id)->first();
        $data->Total_Bayar = $total*$baru->Harga_Satuan;
        $data->save();

        $baru->Stok = $baru->Stok - $total;
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
            'Jumlah' => 'required',
            // 'Total_Bayar' => 'required',
        ],[
            'Kode_Penjualan.required' => 'Kode Penjualan Tidak Boleh Kosong',
            'Tanggal_Jual.required' => 'Harus Diisi',
            'Nama_Pelanggan.required' => 'Nama Pelanggan Tidak Boleh Kosong',
            'Barang_id.required' => 'Harga Harus Diisi',
            'Jumlah.required' => 'Jumlah Harus Diisi',
            // 'Total_Bayar.required' => 'Harus Diisi',
        ]);
        $data = Penjualan::findOrFail($id);
        $data->Kode_Penjualan = $request->Kode_Penjualan;
        $data->Tanggal_Jual = $request->Tanggal_Jual;
        $data->Nama_Pelanggan = $request->Nama_Pelanggan;
        $data->Barang_id = $request->Barang_id;
        $data->Jumlah = $request->Jumlah;
        $total = $request->Jumlah;
        $baru = Barang::where('id', $data->Barang_id)->first();
        $data->Total_Bayar = $total*$baru->Harga_Satuan;
        $data->save();
        return response()->json(['success'=>true]);
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
