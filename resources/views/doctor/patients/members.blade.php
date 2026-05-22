@extends("doctor.template.layout")

@section("header")
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop


@section("content")

<div class="card mb-5">
    
    <div class="card-header"><a href="{{route('doctor.patients.createMember', $patient_id)}}" class="btn btn-primary" style="width: 200px;"><i class="mdi mdi-plus me-1"></i> Add Member </a></div>
   
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-condensed table-striped" id="table_list">
                <thead>
                    <tr>
                        <th>#</th>
                        <!-- <th>Patient Name</th> -->
                        <th>Full Name</th>
                        
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = $records->perPage() * ($records->currentPage() - 1); ?>
                    @foreach($records as $row)
                    <?php $i++ ?>
                    <tr>
                        <td>{{$i}}</td>
                        <!-- <td>{{$row->user->first_name ?? null}} {{$row->user->last_name ?? null}}</td> -->
                        <td>{{$row->full_name}}</td>
                        
                        <td>{{ $row->gender == 1 ? 'Male' : 'Female' }}</td>
                        <td>{{ $row->age}}</td>
                        <td class="">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                    
                                    <a class="dropdown-item" href="{{route('doctor.patients.editMember', ['patient_id' => $row->user_id, 'id' => encrypt($row->id)])}}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    
                                    @if (get_user_permission('patients', 'd'))
                                        <a class="dropdown-item" data-role="unlink"
                                            data-message="Do you want to remove the member?  This may be linked with other sections"
                                            href="{{ route('doctor.patients.deleteMember', ['id' => encrypt($row->id)]) }}">
                                            <i class="flaticon-delete-1"></i> Delete
                                        </a>
                                    @endif
                                    
                                </div>
                            </div>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>


            <div class="mt-4">
                <span>Total {{ $records->total() }} entries</span>
                <div class="col-sm-12 col-md-12 pull-right">
                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                        {!! $records->appends(request()->input())->links('doctor.template.pagination') !!}
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


<script>
    $('#example2').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
    });
</script>

<script>
    App.initFormView();
</script>
@stop