@extends('template')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Tables Penjualan</h1>
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
            <div class="card-header" style="margin-bottom: 15px">
              <button type="button" name="add" id="Tambah" class="btn btn-primary">Add Data</button>
            </div>
              <div class="panel panel-body">
                 <table id="jual_table" class="table table-bordered" style="width:100%">
                    <thead>
                       <tr>
                          <th>Kode Penjualan</th>
                          <th>Tanggal Jual</th>
                          <th>Nama Pelanggan</th>
                          <th>Barang</th>
                          <th>Kategori Barang</th>
                          <th>Jumlah Pesanan</th>
                          <th>Total Yang Harus Dibayar</th>
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

  @include('penjual.modaljual')
      <script type="text/javascript">
         $(document).ready(function() {

          $('#jual_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/jsonjual',
            columns:[
                  { data: 'Kode_Penjualan', name: 'Kode_Penjualan' },
                  { data: 'Tanggal_Jual', name: 'Tanggal_Jual' },
                  { data: 'Nama_Pelanggan', name: 'Nama_Pelanggan'},
                  { data: 'jual' },
                  { data: 'kategori'},
                  { data: 'Jumlah', name: 'Jumlah' },
                  { data: 'formatharga', name: 'formatharga'},
                  { data: 'action', orderable: false, searchable: false }
              ],
            });

           $('#Tambah').click(function(){

            $('#jualModal').modal('show');
            $('.modal-title').text('Add Data');
            $('#aksi').val('Simpan');
            $('.select-dua').select2();
            state = "insert";

            });

           $('#jualModal').on('hidden.bs.modal',function(e){
            $(this).find('#jual_form')[0].reset();
            $('span.has-error').text('');
            $('.form-group.has-error').removeClass('has-error');
            });

          $('#jual_form').submit(function(e){
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
                url: "{{url ('/storejual')}}",
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
                  $('#jualModal').modal('hide');
                  $('#jual_table').DataTable().ajax.reload();
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
                url: "{{url ('jual/edit')}}"+ '/' + $('#id').val(),
                // data: $('#student_form').serialize(),
                data: new FormData(this),
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (data){
                  console.log(data);
                  $('#jualModal').modal('hide');
                  swal({
                    title: 'Update Success',
                    text: data.message,
                    type: 'success',
                    timer: '3500'
                  })
                  $('#jual_table').DataTable().ajax.reload();
                },
                error: function (data){
                  $('#jualModal').modal('show');
                  swal({
                    title: 'Update error',
                    text: 'tidak terpenuhi',
                    type: 'error',
                    timer: '3500'
                  })
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
              url:"{{url('jual/getedit')}}" + '/' + bebas,
              method:'get',
              data:{id:bebas},
              dataType:'json',
              success:function(data){
                console.log(data);
                state = "update";

                $('#id').val(data.id);
                $('#Kode_Penjualan').val(data.Kode_Penjualan);
                $('#Tanggal_Jual').val(data.Tanggal_Jual);
                $('#Nama_Pelanggan').val(data.Nama_Pelanggan);
                $('#Barang_id').val(data.Barang_id);
                $('#kat_id').val(data.kat_id);
                $('#Jumlah').val(data.Jumlah);
                $('.select-dua').select2();


                  $('#jualModal').modal('show');
                  $('#aksi').val('Update');
                  $('.modal-title').text('Edit Data');
                }
              });
          });

          $(document).on('hide.bs.modal','#jualModal', function() {
            $('#jual_table').DataTable().ajax.reload();
          });

          //proses delete data
          $(document).on('click', '.delete', function(){
            var bebas = $(this).attr('id');
              if (confirm("Yakin Dihapus ?")) {

                $.ajax({
                  url: "{{route('ajaxdata.removedatajual')}}",
                  method: "get",
                  data:{id:bebas},
                  success: function(data){
                    swal({
                      title:'Success Delete!',
                      text:'Data Berhasil Dihapus',
                      type:'success',
                      timer:'1500'
                    });
                    $('#jual_table').DataTable().ajax.reload();
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
          $(document).ready(function() {
        $('select[name="kat_id"]').on('change', function() {
            var katID = $(this).val();
            if(katID) {
                $.ajax({
                    url: '/myform/ajax/'+katID,
                    type: "GET",
                    dataType: "json",
                    success:function(data) {

                        
                        $('select[name="sub_id"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="sub_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                });
            }else{
                $('select[name="sub_id"]').empty();
            }
        });
    });
       });
      </script>
      @endpush