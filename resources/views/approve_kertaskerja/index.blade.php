@extends('layouts.app')

@section('content')

<div class="page-content">
	<div class="container-fluid">
		<!-- start page title -->
		<div class="row">
			<div class="col-12">
				<div class="page-title-box d-sm-flex align-items-center justify-content-between">
					<h4 class="mb-sm-0">{{ $menu }}</h4>
					<div class="page-title-right">
						<ol class="breadcrumb m-0">
							<li class="breadcrumb-item">
								<a href="javascript: void(0);">{{ $headermenu }}</a>
							</li>
							<li class="breadcrumb-item active">{{ $menu }}</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<!-- end page title -->

		<div class="row">
			<div class="col-lg-12">
                <div class="row pb-3">
                    <div class="col-sm-12 col-md-6">
                        {{-- <span class="js-upload-file-btn btn btn-sm btn-primary waves-effect waves-light "><i class="mdi mdi-plus-circle-outline"></i> Tambah</span> --}}
                        <button onclick="tambah(0)" class="btn btn-sm btn-success waves-effect waves-light "><i class="mdi mdi-plus-circle-outline"></i> Tambah</button>
                        {{-- <span class="btn btn-sm btn-success waves-effect waves-light "><i class="mdi mdi-plus-circle-outline"></i> Tambah</span> --}}
                    </div>
                    <div class="col-sm-12 col-md-6">

                    </div>
                </div>
				<div class="card">
					<!-- <div class="card-header">
					</div> -->
					<div class="card-body small">
						<div id="example_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
							<div class="row">
								<div class="col-sm-12">
									<table id="data-table-fixed-header" class="table table-bordered table-responsive dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed" style="width: 100%;" >
                                        <thead>
                                            <tr>
                                                <th width="1%" scope="col">No</th>
                                                <th >PKPT</th>
                                                <th >File</th>
                                                <th >Status</th>
                                                <th width="5%" >Action</th>
                                            </tr>
                                        </thead>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="modalAdd" role="dialog" aria-labelledby="exampleModalLabelDefault" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabelDefault">{{ $menu }}</h5>
					</div>
					<div class="modal-body">
						<form id="form-data" enctype="multipart/form-data">
							@csrf
							<div id="tampil-form"></div>
						</form>
					</div>
					<div class="modal-footer">
						<button  class="btn btn-white" onclick="hide()">Tutup</button>
						<button id="btn-save"  class="btn btn-success">Simpan</button>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
@endsection

@push('ajax')
<script text="">
  var handleDataTableFixedHeader = function() {
   "use strict";
   if ($('#data-table-fixed-header').length !== 0) {
       var table=$('#data-table-fixed-header').DataTable({
           lengthMenu: [20, 40, 60],
           fixedHeader: {
               header: true,
               headerOffset: $('#header').height()
           },
           responsive: true,
           ajax:"{{ url('pelaksanaan/approve-kertas-kerja/get-data')}}",
           columns: [
               { data: 'id', render: function (data, type, row, meta)
                   {
                       return meta.row + meta.settings._iDisplayStart + 1;
                   }
               },
               { data: 'id_pkpt' },
               { data: 'file' },
               { data: 'status' },
               { data: 'action' },
           ],
           language: {
               paginate: {
                   previous: '<< previous',
                   next: 'Next>>'
               }
           }
       });
   }
};

var TableManageFixedHeader = function () {
   "use strict";
   return {
       //main function
       init: function () {
           handleDataTableFixedHeader();
       }
   };
}();

$(document).ready(function() {
   TableManageFixedHeader.init();

});
</script>

<script>

        function tambah(id){
			$('#btn-save').removeAttr('disabled','false');
			$.ajax({
				type: 'GET',
				url: "{{url('pelaksanaan/approve-kertas-kerja/modal')}}",
				data: "id="+id,
				success: function(msg){
					$('#tampil-form').html(msg);
					$('#modalAdd').modal('show');
				}
			});
		}

        function hide(){
        $('#modalAdd').modal('hide');
        }

        $('#btn-save').on('click', () => {
        var form=document.getElementById('form-data');
            $.ajax({
                type: 'POST',
                url: "{{url('pelaksanaan/approve-kertas-kerja/store')}}",
                data: new FormData(form),
                contentType: false,
                cache: false,
                processData:false,
                dataType: 'json',
                beforeSend: function () {
                    $('#btn-save').attr('disabled', 'disabled');
                    $('#btn-save').html('Sending..');
                },
                error: function (msg) {
                        var data = msg.responseJSON;
                        $.each(data.errors, function (key, value) {
                            Swal.fire({
                                title: 'Gagal',
                                text: value,
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                $('#btn-save').removeAttr('disabled','false');
                                $('#btn-save').html('Simpan');
                            }
                        })
                        });
                },
                success:  function (msg) {
                    if (msg.status == 'success') {
                        Swal.fire({
                            title: 'Berhasil',
                            text: msg.message,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{url('pelaksanaan/approve-kertas-kerja')}}";
                            }
                        })
                    }
                }
            });
    });

    function hapus(id){
			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Delete This!'
			}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					type: 'GET',
                    url: "{{url('pelaksanaan/approve-kertas-kerja/destroy')}}",
                    data: "id=" + id,
					success: function(msg){
                        if(msg.status=='success'){
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            location.reload();
                        }else{
                            Swal.fire(
                                'Failed!',
                                'Your file failed to delete.',
                                'error'
                            )
                        }
					}
				});
			}
			});
		}


    function approved(id){
			Swal.fire({
				title: 'Are you sure?',
				text: "Apakah anda yakin ingin menyetujui data ini?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'OK'
			}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					type: 'GET',
                    url: "{{url('pelaksanaan/approve-kertas-kerja/approved')}}",
                    data: "id=" + id,
					success: function(msg){
                        if(msg.status=='success'){
                            Swal.fire(
                                'Approved!',
                                msg.message,
                                'success'
                            )
                            location.reload();
                        }
					}
				});
			}
			});
		}

    function refused(id){
			Swal.fire({
				title: 'Are you sure?',
				text: "Apakah anda yakin ingin menolak data ini?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'OK'
			}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					type: 'GET',
                    url: "{{url('pelaksanaan/approve-kertas-kerja/refused')}}",
                    data: "id=" + id,
					success: function(msg){
                        if(msg.status=='success'){
                            Swal.fire(
                                'Refused!',
                                msg.message,
                                'success'
                            )
                            location.reload();
                        }
					}
				});
			}
			});
		}
</script>
@endpush
