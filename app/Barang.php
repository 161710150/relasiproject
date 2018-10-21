<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = ['suplier_id','kat_id','id_parent', 'Merk', 'Harga_Satuan', 'Stok'];
    public $timestamp = true;

    public function suplier(){
    	return $this->belongsTo('App\Suplier','suplier_id');
    }

    public function penjualan(){
    	return $this->hasOne('App\Penjualan','Barang_id');
    }
    public function kategoribarang(){
    	return $this->belongsTo('App\KategoriBarang', 'kat_id');
    }
    public function subbarang(){
        return $this->belongsTo('App\KategoriBarang', 'id_parent');
    }
}
