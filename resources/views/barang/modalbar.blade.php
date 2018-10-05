<!DOCTYPE html>
<html>
<head>
	<title>Barang</title>
</head>
<body>
	<div id="barangModal" class="modal fade" role="dialog" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog">
         <div class="modal-content">
            <form method="post" id="barang_form" enctype="multipart/form-data">
               <div class="modal-header" style="background-color: lightblue;">
                  <h4 class="modal-title" >Add Data</h4>
                  <button type="button" class="close" data-dismiss="modal" >&times;</button>
               </div>

               <div class="modal-body">
                  {{csrf_field()}} {{ method_field('POST') }}
                  <span id="form_tampil"></span>
               
                  <div class="form-group {{ $errors->has('suplier_id') ? 'has-error' : '' }}">
                     <input type="hidden" name="id" id="id">

                     <label>Nama Suplier</label>
                     <select class="form-control select-dua" name="suplier_id" id="suplier_id" style="width: 468px">
                     	<option disabled selected>Pilih Suplier</option>
                     	@foreach($suplier as $data)
                     	<option value="{{$data->id}}">{{$data->Nama}}</option>
                     	@endforeach
                     </select>
                     @if ($errors->has('suplier_id'))
                     <span class="help-block has-error Nama_error">
                     	<strong>{{$errors->first('suplier_id')}}</strong>
                     </span>
                     @endif
                  </div>

                  <div class="form-group">
                  	<label>Nama Barang</label>
                  	<input type="text" id="Nama_Barang" name="Nama_Barang" class="form-control" placeholder="masukan nama barang">
                  	<span class="help-block has-error Nama_Barang_error"></span>
                  </div>

                  <div class="form-group">
                  	<label>Merk Barang</label>
                  	<input type="text" id="Merk" name="Merk" class="form-control" placeholder="masukan merk barang">
                  	<span class="help-block has-error Merk_error"></span>
                  </div>

                  <div class="form-group">
                  	<label>Harga Satuan ( Rupiah )</label>
                  	<input type="text" id="Harga_Satuan" name="Harga_Satuan" class="form-control" placeholder="Rp.">
                  	<span class="help-block has-error Harga_Satuan_error"></span>
                  </div>

                  <div class="form-group">
                  	<label>Stok Barang</label>
                  	<input type="number" id="Stok" name="Stok" class="form-control" placeholder="jumlah stok barang">
                  	<span class="help-block has-error Stok_error"></span>
                  </div>

				<div class="modal-footer">
					<input type="submit" name="submit" id="aksi" value="Tambah" class="btn btn-info" />
					<input type="button" value="Cancel" class="btn btn-default" data-dismiss="modal"/>
				</div>
               </form>
            </div>
         </div>
      </div>

</body>
</html>