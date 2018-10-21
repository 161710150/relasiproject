@extends('template')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Data Tables Barang</h1>
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
               <table id="bar_table" class="table table-bordered" style="width:100%">
                  <thead>
                     <tr>
                        <th>Nama Suplier</th>
                        <th>Kategori Barang</th>
                        <th>Nama Barang</th>
                        <th>Merk Barang</th>
                        <th>Harga Satuan</th>
                        <th>Stok</th>
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

@include('barang.modalbar')

<script type="text/javascript">
   $(document).ready(function() {

    $('#bar_table').DataTable({
      processing: true,
      serverSide: true,
      ajax: 'jsonbarang',
      columns:[
            { data: 'supliername' },
            { data: 'kategori'},
            { data: 'parent'},
            { data: 'Merk', name: 'Merk' },
            { data: 'formatharga', name: 'formatharga' },
            { data: 'Stok', name: 'Stok'},
            { data: 'action', orderable: false, searchable: false }
        ],
      });
    $('#Tambah').click(function(){

      $('#barangModal').modal('show');
      $('.modal-title').text('Add Data');
      $('#aksi').val('Tambah');
      $('.select-dua').select2();
      state = "insert";

      });

    $('#barangModal').on('hidden.bs.modal',function(e){
      $(this).find('#barang_form')[0].reset();
      $('span.has-error').text('');
      $('.form-group.has-error').removeClass('has-error');
      });

    $('#barang_form').submit(function(e){
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
          url: "{{url ('/storebar')}}",
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
            $('#barangModal').modal('hide');
            $('#bar_table').DataTable().ajax.reload();
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
          url: "{{url ('barang/edit')}}"+ '/' + $('#id').val(),
          // data: $('#student_form').serialize(),
          data: new FormData(this),
          contentType: false,
          processData: false,
          dataType: 'json',
          success: function (data){
            console.log(data);
            $('#barangModal').modal('hide');
            swal({
              title: 'Update Success',
              text: data.message,
              type: 'success',
              timer: '3500'
            })
            $('#bar_table').DataTable().ajax.reload();
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
        url:"{{url('barang/getedit')}}" + '/' + bebas,
        method:'get',
        data:{id:bebas},
        dataType:'json',
        success:function(data){
          console.log(data);
          state = "update";

          $('#id').val(data.id);
          $('#suplier_id').val(data.suplier_id);
          $('#kat_id').val(data.kat_id);
          $('#id_parent').val(data.id_parent);
          $('#Merk').val(data.Merk);
          $('#Harga_Satuan').val(data.Harga_Satuan);
          $('#Stok').val(data.Stok);
          $('.select-dua').select2();


            $('#barangModal').modal('show');
            $('#aksi').val('Simpan');
            $('.modal-title').text('Edit Data');
          }
        });
    });

    $(document).on('hide.bs.modal','#barangModal', function() {
      $('#bar_table').DataTable().ajax.reload();
    });

    //proses delete data
    $(document).on('click', '.delete', function(){
      var bebas = $(this).attr('id');
        if (confirm("Yakin Dihapus ?")) {

          $.ajax({
            url: "{{route('ajaxdata.removedatabar')}}",
            method: "get",
            data:{id:bebas},
            success: function(data){
              swal({
                title:'Success Delete!',
                text:'Data Berhasil Dihapus',
                type:'success',
                timer:'1500'
              });
              $('#bar_table').DataTable().ajax.reload();
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

                        
                        $('select[name="id_parent"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="id_parent"]').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                    }
                });
            }else{
                $('select[name="id_parent"]').empty();
            }
        });
    });
    });
</script>
@endpush      