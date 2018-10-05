<?php

namespace App\Http\Controllers;

use App\Suplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\DataTables;

class SuplierController extends Controller
{
    public function json()
    {
        $suplier = Suplier::all();
        return Datatables::of($suplier)
        ->addColumn('action', function($suplier){
            return '<a href="#" class="btn btn-xs btn-primary edit" data-id="'.$suplier->id.'">
            <i class="glyphicon glyphicon-edit"></i> Edit</a>&nbsp;
            <a href="#" class="btn btn-xs btn-danger delete" id="'.$suplier->id.'">
            <i class="glyphicon glyphicon-remove"></i> Delete</a>';

            })
        ->rawColumns(['action'])->make(true);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('suplier.index');
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
            'Nama' => 'required',
            'Jenis_Kelamin' => 'required',
            'Asal_Kota' => 'required',
        ],[
            'Nama.required' => 'Nama Tidak Boleh Kosong',
            'Jenis_Kelamin.required' => 'Harus Diisi',
            'Asal_Kota.required' => 'Harus Diisi',
        ]);
        $data = new Suplier;
        $data->Nama = $request->Nama;
        $data->Jenis_Kelamin = $request->Jenis_Kelamin;
        $data->Asal_Kota = $request->Asal_Kota;
        $data->save();
        return response()->json(['success'=>true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Suplier  $suplier
     * @return \Illuminate\Http\Response
     */
    public function show(Suplier $suplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Suplier  $suplier
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $suplier = Suplier::findOrFail($id);
        return $suplier;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Suplier  $suplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'Nama'=>'required',
            'Jenis_Kelamin' => 'required',
            'Asal_Kota'=>'required',
        ],[
            'Nama.required' => 'Nama Tidak Boleh Kosong',
            'Jenis_Kelamin.required' => 'Harus Diisi',
            'Asal_Kota.required' => 'Tidak Boleh Kosong',
        ]);
        $suplier = Suplier::findOrFail($id);
        $suplier->Nama = $request->Nama;
        $suplier->Jenis_Kelamin = $request->Jenis_Kelamin;
        $suplier->Asal_Kota = $request->Asal_Kota;
        $success = $suplier->save();
        if ($success){
            return response()->json([
                'success'=>true,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Suplier  $suplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Suplier $suplier)
    {
        //
    }
    public function removedata(Request $request)
    {
        $suplier = Suplier::find($request->input('id'));
        if($suplier->delete())
        {
            echo 'Data Deleted';
        }
    }
}
