@extends('template')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Tables Suplier</h1>
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
              <button type="button" name="add" id="Tambah" class="btn btn-primary pull-right" style="margin-left: 1120px; margin-top: 10px; margin-bottom: 10px">Add Data</button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="suplier_table" class="table table-bordered" style="width:100%">
                  <thead>
                     <tr>
                        <th>Nama Suplier</th>
                        <th>Jenis Kelamin</th>
                        <th>Asal Kota</th>
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

@include('suplier.modalsuplier')
      <script type="text/javascript">
         $(document).ready(function() {

          $('#suplier_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/jsondata',
            columns:[
                  { data: 'Nama', name: 'Nama' },
                  { data: 'Jenis_Kelamin', name: 'Jenis_Kelamin' },
                  { data: 'Asal_Kota', name: 'Asal_Kota'},
                  { data: 'action', orderable: false, searchable: false }
              ],
            });

          $('#Tambah').click(function(){

            $('#suplierModal').modal('show');
            $('.modal-title').text('Add Data');
            $('#aksi').val('Tambah');
            $('.selectdua').select2();
            state = "insert";

            });
          $('#suplierModal').on('hidden.bs.modal',function(e){
            $(this).find('#suplier_form')[0].reset();
            $('span.has-error').text('');
            $('.form-group.has-error').removeClass('has-error');
            });

          $('#suplier_form').submit(function(e){
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
                url: "{{url ('/store')}}",
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
                  $('#suplierModal').modal('hide');
                  $('#suplier_table').DataTable().ajax.reload();
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
                url: "{{url ('suplier/edit')}}"+ '/' + $('#id').val(),
                // data: $('#student_form').serialize(),
                data: new FormData(this),
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (data){
                  console.log(data);
                  $('#suplierModal').modal('hide');
                  swal({
                    title: 'Update Success',
                    text: data.message,
                    type: 'success',
                    timer: '3500'
                  })
                  $('#suplier_table').DataTable().ajax.reload();
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
              url:"{{url('suplier/getedit')}}" + '/' + bebas,
              method:'get',
              data:{id:bebas},
              dataType:'json',
              success:function(data){
                console.log(data);
                state = "update";

                $('#id').val(data.id);
                $('#Nama').val(data.Nama);
                 if (data.Jenis_Kelamin == 'Laki-laki') {
                    $('#laki').prop('checked', true);
                  }
                  else
                  {
                    $('#wanita').prop('checked', true);
                  }
                $('#Asal_Kota').val(data.Asal_Kota);
                $('.selectdua').select2();


                  $('#suplierModal').modal('show');
                  $('#aksi').val('Simpan');
                  $('.modal-title').text('Edit Data');
                }
              });
          });

          $(document).on('hide.bs.modal','#suplierModal', function() {
            $('#suplier_table').DataTable().ajax.reload();
          });

          //proses delete data
          $(document).on('click', '.delete', function(){
            var bebas = $(this).attr('id');
              if (confirm("Yakin Dihapus ?")) {

                $.ajax({
                  url: "{{route('ajaxdata.removedatasup')}}",
                  method: "get",
                  data:{id:bebas},
                  success: function(data){
                    swal({
                      title:'Success Delete!',
                      text:'Data Berhasil Dihapus',
                      type:'success',
                      timer:'1500'
                    });
                    $('#suplier_table').DataTable().ajax.reload();
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