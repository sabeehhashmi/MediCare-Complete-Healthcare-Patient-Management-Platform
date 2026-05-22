@extends("admin.template.layout")

@section("header")
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop


@section("content")

<div class="card mb-5">
    @if(get_user_permission('agents','c'))
    <div class="card-header"><a href="{{route('admin.agents.create')}}?call_center={{$call_center_id}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i class="mdi mdi-plus me-1"></i> Add Agent </a></div>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = $agents->perPage() * ($agents->currentPage() - 1); ?>
                    @foreach($agents as $agent)
                    <?php $i++ ?>
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$agent->name}}</td>
                        <td>{{$agent->email}}</td>
                        <td>+{{ $agent->dial_code }} {{ $agent->phone }}</td>

                        <td>{{ isset($agent->agentDetails->country)?$agent->agentDetails->country->name:'' }}</td>

                        <td>{{ isset($agent->agentDetails->emirate) ? $agent->agentDetails->emirate->name_en : 'N/A' }}</td>
                        <td>{{ isset($agent->agentDetails->area) ? $agent->agentDetails->area->name_en : '' }}</td>
                        <td class="">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-horizontal-rounded"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @php
                                    $agent_id=isset($agent->agentDetails)?$agent->agentDetails->id:'';
                                @endphp
                            @if(get_user_permission('agents','u'))
                                    <a class="dropdown-item" href="{{route('admin.agents.edit', ['id'=> $agent_id])}}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    @endif
                                    @if($agent_id)
                                    <a class="dropdown-item" data-role="unlink"
                                        data-message="Do you want to remove the agent? This may be linked with other sections"
                                        href="{{ route('admin.agents.delete', ['id' => encrypt($agent_id)]) }}">
                                        <i class="flaticon-delete-1"></i> Delete Agent
                                    </a>
                                    @endif
                                    <a class="dropdown-item" href="{{route('admin.agents.hospital', ['id'=> $agent->id])}}"><i class="flaticon-pencil-1"></i> Hospital</a>
                                    <a class="dropdown-item" href="#"><i class="flaticon-pencil-1"></i> Clinic</a>
                                    <a class="dropdown-item" href="{{route('admin.agents.doctors', ['id'=> $agent->id])}}"><i class=""></i> Doctors</a>
                                    <a class="dropdown-item" href="{{route('admin.agents.appointments', ['id'=> $agent->id])}}"><i class=""></i>  Appointments</a>


                                </div>
                            </div>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>


            <div class="mt-4">
                <span>Total {{ $agents->total() }} entries</span>
                <div class="col-sm-12 col-md-12 pull-right">
                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                        {!! $agents->appends(request()->input())->links('admin.template.pagination') !!}
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
