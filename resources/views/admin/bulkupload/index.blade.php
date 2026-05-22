@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/assets/css/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @stop



@section("content")

<div class="card mb-5">

    <div class="card-body">
        @if(session()->has('success'))
            <div class="alert alert-success">
                {!! session()->get('success') !!}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger">
                {!! session()->get('error') !!}
            </div>
        @endif
        <div class="row">
            <div class="col-md-6 col-12">
                    <div class="card mb-5">
                        <div class="card-header">
                            <h4>Hospital/Clinic Bulk Upload</h4>
                        </div>
                        <div class="card-body border-bottom">
                                <form action="{{ route('admin.hospitals.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="file">Choose Excel file to import:</label>
                                    <input type="file" class="form-control" id="file" name="file" required accept=".xlsx, .xls">
                                </div>
                                <br>
                                <div class="form-group d-flex  align-items-center">
                                <button type="submit" class="btn btn-primary me-2">Import Data</button>
                                <a href="{{route('admin.hospitals.export_excel')}}?blank=1"  style="padding:0 10px !important;">Export Blank Excel</a>
                                </div>
                            </form>

                            
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.hospitals.upload-hospital-zip') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="zip_file">Upload ZIP File:</label>
                                    <input type="file" class="form-control" name="zip_file" id="zip_file" required>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Upload and Extract</button>
                            </form>
                        </div>
                    </div>
            </div>
            <div class="col-md-6 col-12">
                    <div class="card mb-5">
                        <div class="card-header">
                            <h4>Doctor Bulk Upload</h4>
                        </div>
                        <div class="card-body border-bottom">
                                <form method="get" action="{{route('admin.doctors.export_excel')}}?blank=1" >
                                    <div class="form-group">
                                        <label>Hospital/Clinic</label>
                                        <div class="position-relative select-custom-icon">
                                            <select name="hospital_id" class="form-control jqv-input">
                                                @foreach($hospital_list as $h)
                                                    <option value="{{$h->id}}">{{$h->name_en}}</option>
                                                @endforeach
                                            </select>
                                            <i class="fi fi-rr-hospital icon d-flex"></i>
                                        </div>
                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-primary">Export Blank Excel</button>
                                </form>
                                <br>
                                <form action="{{ route('admin.doctors.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="file">Choose Excel file to import:</label>
                                    <input type="file" class="form-control" id="file" name="file" required accept=".xlsx, .xls">
                                </div>
                                <br>
                                <div class="form-group">
                                <button type="submit" class="btn btn-primary">Import Data</button>
                                <!-- <a href="{{route('admin.doctors.export_excel')}}?blank=1"  style="padding:0 10px !important;">Export Blank Excel</a>  -->
                                </div>
                            </form>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.doctors.upload-doctor-zip') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="zip_file">Upload ZIP File:</label>
                                    <input type="file" class="form-control" name="zip_file" id="zip_file" required>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Upload and Extract</button>
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>


@stop

@section("page_script")

<script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/flatpickr.min.js"></script>

   <!-- Intel Input Js-->


@stop
