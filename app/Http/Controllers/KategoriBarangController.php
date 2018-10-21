<?php

namespace App\Http\Controllers;

use App\KategoriBarang;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\DataTables;

class KategoriBarangController extends Controller
{
    public function json()
    {
        $kategori = KategoriBarang::all();
        return Datatables::of($kategori)
        ->addColumn('action', function($kategori){
            return '<a href="#" class="btn btn-xs btn-primary edit" data-id="'.$kategori->id.'">
            <i class="glyphicon glyphicon-edit"></i> Edit</a>&nbsp;
            <a href="#" class="btn btn-xs btn-danger delete" id="'.$kategori->id.'">
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
        $kategori = KategoriBarang::where('parent_id','=',null)->get();
        return view('kategori.index', compact('kategori'));
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
            'Nama_Kategori' => 'required',
        ],[
            'Nama_Kategori.required' => 'Nama Kategori Tidak Boleh Kosong',
        ]);
        $data = new KategoriBarang;
        $data->Nama_Kategori = $request->Nama_Kategori;
        $data->parent_id = $request->parent_id;
        $data->save();
        return response()->json(['success'=>true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KategoriBarang  $kategoriBarang
     * @return \Illuminate\Http\Response
     */
    public function show(KategoriBarang $kategoriBarang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KategoriBarang  $kategoriBarang
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kategori = KategoriBarang::findOrFail($id);
        return $kategori;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KategoriBarang  $kategoriBarang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'Nama_Kategori' => 'required',
        ],[
            'Nama_Kategori.required' => 'Nama Kategori Tidak Boleh Kosong',
        ]);
        $data = KategoriBarang::findOrFail($id);
        $data->Nama_Kategori = $request->Nama_Kategori;
        $data->parent_id = $request->parent_id;
        $data->save();
        return response()->json(['success'=>true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KategoriBarang  $kategoriBarang
     * @return \Illuminate\Http\Response
     */
    public function destroy(KategoriBarang $kategoriBarang)
    {
        //
    }
    public function removedata(Request $request)
    {
        $kategori = KategoriBarang::find($request->input('id'));
        if($kategori->delete())
        {
            echo 'Data Deleted';
        }
    }
}
