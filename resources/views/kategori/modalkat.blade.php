<!DOCTYPE html>
<html>
<head>
	<title>Kategori</title>
</head>
<body>
	<div id="Modalkat" class="modal fade" role="dialog" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog">
         <div class="modal-content">
            <form method="post" id="formkat" enctype="multipart/form-data">
               <div class="modal-header" style="background-color: lightblue;">
               	<h5 class="modal-title" >Add Data</h5>
                  <button type="button" class="close" data-dismiss="modal" >&times;</button>
               </div>

               <div class="modal-body">
                  {{csrf_field()}} {{ method_field('POST') }}
                  <span id="form_tampil"></span>
               
                  <div class="form-group">
                     <input type="hidden" name="id" id="id">
               
                     <label>Nama Kategori/Barang</label>
                     <input type="text" name="Nama_Kategori" id="Nama_Kategori" class="form-control" placeholder="masukan data" />
                     <span class="help-block has-error Nama_Kategori_error"></span>
                  </div>

                  <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">
                     <label>Kategori</label>
                     <select class="form-control select-dua" name="parent_id" id="parent_id" style="width: 468px">
                        <option disabled selected>Pilih Kategori Barang</option>
                        @foreach($kategori as $data)
                        <option value="{{$data->id}}">{{$data->Nama_Kategori}}</option>
                        @endforeach
                     </select>
                     <span class="help-block has-error parent_id_error">
                  </div>
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