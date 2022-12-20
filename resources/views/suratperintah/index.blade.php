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
				<div class="card">
					<div class="card-body small">
						<div id="example_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
							<div class="row">
								<div class="col-sm-12">
									<table id="data-table-fixed-header" class="table table-bordered table-responsive dt-responsive nowrap table-striped align-middle dataTable no-footer dtr-inline collapsed" style="width: 100%;" >
                                        <thead>
                                            <tr>
                                                <th width="1%" scope="col">No</th>
                                                <th >Nomor PKPT</th>
                                                <th >Jenis</th>
                                                <th >PKP</th>
                                                <th >Nota Dinas</th>
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
        <div class="modal fade" id="modalshow" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabelDefault">{{ $menu }}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"
								aria-label="Close">
						</button>
					</div>
					<div class="modal-body">
						<div id="error-notif"></div>
						<form id="form-refused" enctype="multipart/form-data">
							@csrf
							<div id="tampil-pdf"></div>
						</form>
					</div>
					<div class="modal-footer">
						<button  class="btn btn-white" onclick="hide()">Tutup</button>
						<button id="btn-refused"  class="btn btn-success">Simpan</button>
					</div>
				</div>
			</div>
		</div>
        <div class="modal fade" id="tampiltable" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabelDefault">{{ $menu }}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"
								aria-label="Close">
						</button>
					</div>
					<div class="modal-body" style="max-height: calc(100vh - 210px);overflow-y: auto;">
						<div id="error-notif"></div>
						<form id="form-refused" enctype="multipart/form-data">
							@csrf
							<div id="tampil-table"></div>
						</form>
					</div>
					<div class="modal-footer">
						<button  class="btn btn-white" onclick="hide()">Tutup</button>
						<button id="btn-refused"  class="btn btn-success">Simpan</button>
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
           ajax:"{{ url('perencanaan/surat-perintah/get-data')}}",
           columns: [
               { data: 'id', render: function (data, type, row, meta)
                   {
                       return meta.row + meta.settings._iDisplayStart + 1;
                   }
               },
               { data: 'area_pengawasannya' },
               { data: 'jenis' },
               { data: 'pkp' },
               { data: 'nota_dinas' },
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
    function download(id){
        location.href = "{{ url('perencanaan/surat-perintah/download?id=') }}"+id;
    }
    function buka_file(file){
        $('#modalshow').modal('show');
        var files=file.split(".");
        var surat =files[3];
        if(surat=='pdf'){
            $('#tampil-pdf').html('<embed src="{{ url('public/file_upload') }}/'+file+'" width="100%" height="500px">');
        }else{
            $('#tampil-pdf').html('<embed src="{{ url('public/file_upload') }}/'+file+'" width="100%" height="500px">');
        }
    }
    function tampil(id){
        $('#btn-save').removeAttr('disabled','false');
        $.ajax({
            type: 'GET',
            url: "{{url('perencanaan/program-kerja-pengawasan/tampil-table')}}",
            data: "id="+id,
            success: function(msg){
                $('#tampil-table').html(msg);
                $('#tampiltable').modal('show');
            }
        });
    }
</script>

@endpush