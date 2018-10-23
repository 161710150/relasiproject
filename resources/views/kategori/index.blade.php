@extends('template')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Tables Kategori</h1>
          </div>
          <!-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Data Tables</li>
            </ol>
          </div> -->
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <button type="button" name="add" id="Tambah" class="btn btn-primary">Add Data</button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tablekat" class="table table-bordered" style="width:100%">
                  <thead>
                     <tr>
                      <th>Nomor Id</th>
                      <th>Nama Barang dan Kategori</th>
                      <th>Berdasarkan Kategori Id</th>
                      <th>Action</th>
                     </tr>
                  </thead>
               </table>
             </div>
           </div>
         </div>
       </div>
     </section>
   </div>
@endsection
@push('scripts')

@include('kategori.modalkat')
<script type="text/javascript">
         $(document).ready(function() {

          $('#tablekat').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/jsonkategori',
            columns:[
                  { data: 'id'},
                  { data: 'Nama_Kategori', name: 'Nama_Kategori' },
                  { data: 'parent_id', name: 'parent_id'},
                  { data: 'action', orderable: false, searchable: false }
              ],
            });

          $('#Tambah').click(function(){

            $('#Modalkat').modal('show');
            $('.modal-title').text('Add Data');
            $('#aksi').val('Tambah');
            $('.selectdua').select2();
            state = "insert";

            });
          $('#Modalkat').on('hidden.bs.modal',function(e){
            $(this).find('#formkat')[0].reset();
            $('span.has-error').text('');
            $('.form-group.has-error').removeClass('has-error');
            });

          $('#formkat').submit(function(e){
            $.ajaxSetup({
              header: {
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
              }
            });

            //menambah kan data
            e.preventDefault();

            if (state == 'insert'){

              $.ajax({
                type: "POST",
                url: "{{url ('/storekategori')}}",
                data: new FormData(this),
               // data: $('#student_form').serialize(),
                contentType: false,
                processData: false,
                dataType: 'json',

                success: function (data){
                  console.log(data);
                  swal({
                      title:'Success Tambah!',
                      text:'Data Berhasil Disimpan',
                      type:'success',
                      timer:'2000'
                    });
                  $('#Modalkat').modal('hide');
                  $('#tablekat').DataTable().ajax.reload();
                },

                //menampilkan validasi error
                error: function (data){

                  $('input').on('keydown keypress keyup click change', function(){
                  $(this).parent().removeClass('has-error');
                  $(this).next('.help-block').hide()
                });

                  var coba = new Array();
                  console.log(data.responseJSON.errors);
                  $.each(data.responseJSON.errors,function(name, value){
                    console.log(name);
                    coba.push(name);

                    $('input[name='+name+']').parent().addClass('has-error');
                    $('input[name='+name+']').next('.help-block').show().text(value);
                  });

                  $('input[name='+coba[0]+']').focus();
                }
              });
            }
            else 
            {
               //mengupdate data yang telah diedit
              $.ajax({
                type: "POST",
                url: "{{url ('kategori/edit')}}"+ '/' + $('#id').val(),
                // data: $('#student_form').serialize(),
                data: new FormData(this),
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (data){
                  console.log(data);
                  $('#Modalkat').modal('hide');
                  swal({
                    title: 'Update Success',
                    text: data.message,
                    type: 'success',
                    timer: '3500'
                  })
                  $('#tablekat').DataTable().ajax.reload();
                },
                error: function (data){
                  $('input').on('keydown keypress keyup click change', function(){
                  $(this).parent().removeClass('has-error');
                  $(this).next('.help-block').hide()
                });
                  var coba = new Array();
                  console.log(data.responseJSON.errors);
                  $.each(data.responseJSON.errors,function(name, value){
                    console.log(name);
                    coba.push(name);
                    $('input[name='+name+']').parent().addClass('has-error');
                    $('input[name='+name+']').next('.help-block').show().text(value);
                  });

                  $('input[name='+coba[0]+']').focus();
                }
             });
            }
         });

          //mengambil data yang ingin diedit
          $(document).on('click', '.edit', function(){
            var bebas = $(this).data('id');
            $('#form_tampil').html('');
            $.ajax({
              url:"{{url('kategori/getedit')}}" + '/' + bebas,
              method:'get',
              data:{id:bebas},
              dataType:'json',
              success:function(data){
                console.log(data);
                state = "update";

                $('#id').val(data.id);
                $('#Nama_Kategori').val(data.Nama_Kategori);

                  $('#Modalkat').modal('show');
                  $('#aksi').val('Simpan');
                  $('.modal-title').text('Edit Data');
                }
              });
          });

          $(document).on('hide.bs.modal','#Modalkat', function() {
            $('#tablekat').DataTable().ajax.reload();
          });

          //proses delete data
          $(document).on('click', '.delete', function(){
            var bebas = $(this).attr('id');
              if (confirm("Yakin Dihapus ?")) {

                $.ajax({
                  url: "{{route('ajaxdata.removedatakategori')}}",
                  method: "get",
                  data:{id:bebas},
                  success: function(data){
                    swal({
                      title:'Success Delete!',
                      text:'Data Berhasil Dihapus',
                      type:'success',
                      timer:'1500'
                    });
                    $('#tablekat').DataTable().ajax.reload();
                  }
                })
              }
              else
              {
                swal({
                  title:'Batal',
                  text:'Data Tidak Jadi Dihapus',
                  type:'error',
                  });
                return false;
              }
            });
       });
</script>
@endpush