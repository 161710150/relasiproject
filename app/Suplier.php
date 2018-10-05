<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suplier extends Model
{
    // protected $table = 'supliers';
    protected $fillable = ['Nama','Jenis_Kelamin','Asal_Kota'];
    public $timestamp = true;

    public function barang(){
    	return $this->hasMany('App\Barang','suplier_id');
    }
}
