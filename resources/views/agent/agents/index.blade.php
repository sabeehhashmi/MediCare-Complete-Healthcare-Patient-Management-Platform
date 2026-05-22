@include('agent.layouts.header')



<div class="card mb-5">
   
<div class="card-header card-header d-flex justify-content-between">
            <a href="{{route('agent.hospitals.create')}}" class="btn btn-primary w-auto ml-auto"> Add Agent</a>
        </div>
   
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
                        <td>{{ $agent->agentDetails->country->name }}</td>
                        <td>{{ $agent->agentDetails->emirate->name_en }}</td>
                        <td>{{ $agent->agentDetails->area->name_en }}</td>
                        <td class="">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                 
                                    <a class="dropdown-item" href="{{route('agent.agents.edit', ['id'=> $agent->id])}}"><i class="flaticon-pencil-1"></i> Edit</a>
                                  
                                    
                                   
                                    <a class="dropdown-item" href="{{route('agent.agents.appointments', ['id'=> $agent->agentDetails->id])}}"><i class=""></i>  Appointments</a>
                                   
                                    
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
@include('agent.layouts.footer')
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
