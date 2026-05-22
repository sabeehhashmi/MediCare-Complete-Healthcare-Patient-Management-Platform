@extends("admin.template.layout")

@section("header")
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop

<link href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

@section("content")

<div class="card mb-5">
    @if(get_user_permission('agents','c'))
    <div class="card-header"><a href="{{route('admin.agents.create')}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i class="mdi mdi-plus me-1"></i> Add Agent </a></div>
    @endif
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-condensed table-striped" id="table_list">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email ID</th>
                        <th>Phone Number</th>
                        <th>Country</th>
                         <th>City</th>
                        <th>Area</th>
                        <th>Call Center</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>
    </div>
</div>
@stop

@section("page_script")
<script src="{{asset('')}}admin-assets/assets/js/dataTables.min.js"></script>
<script src="{{asset('')}}admin-assets/assets/js/dataTables.bootstrap5.min.js"></script>


<script>
    $(document).ready(function () {
    var value = $("#hospital_id").val();
    $('#table_list').DataTable({
        processing: true,
        serverSide: true,
        filter: true,
        ordering: false,
        searching:true,
        ajax: {
            'type':'POST',
            'url' : '{{ route("admin.agents.load") }}',
            'data':{
                '_token': '{{csrf_token()}}',
                'hospital_id': value
            }
        },
        columns: [
            {data: 'sl_no', orderable: false, searchable: false},
            {data: 'name', name: 'users.name'},
            {data: 'email', name: 'users.email'},
            {data: 'phone_number', name: 'users.phone'},
            {data: 'country_name', name: 'country.name'},
            {data: 'emirate_name', name: 'emirates.name_en'},
            {data: 'area_name', name: 'areas.name_en'},
            {data: 'call_center_name', name: 'call_center_name'},
            {data: 'status',   orderable: false, searchable: false},
            {data: 'action',  orderable: false, searchable: false}
        ],
        order: [],
        language: {
            loadingRecords: "No Data Available",
        },
    });

    // Implement search functionality
    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        $('#table_list').DataTable().search($(this).serialize()).draw();
    });
});
</script>

<script>
    App.initFormView();
</script>
@stop
