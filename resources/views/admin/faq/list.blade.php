@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin_assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin_assets/plugins/table/datatable/custom_dt_customer.css">
@stop


@section("content")
<div class="card mb-5">
    @if(get_user_permission('faq','c'))
    <div class="card-header"><a href="{{url('admin/faq/create')}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"> Create FAQ</a></div>
    @endif
    <div class="card-body">
    <div class="dataTables_wrapper container-fluid dt-bootstrap4">
   

    <div class="row">
        <!-- <div class="col-sm-12 col-md-6">
            <div class="dataTables_length" id="column-filter_length">
            </div>
        </div> -->
        
        <form method="get" action='' class="col-sm-12 col-md-8 custom-form">
            <div id="column-filter_filter" class="dataTables_filter">
                <div class="row align-items-end">
                    <div class="col-md-6">
                        <label class="w-100">Search:
                            <input type="search" name="search_key" class="form-control form-control-sm" placeholder="" aria-controls="column-filter" value="{{$search_key}}">
                        </label>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary mb-1">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
        <table class="table table-condensed table-striped">
            <thead>
                <tr>
                <th>#</th>
                <th>Question</th>
                <th>Status</th>
                <th>Created Date</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php $i = $list->perPage() * ($list->currentPage() - 1); ?>
            @foreach($list as $item)
            <?php $i++; ?>
               <tr>
                   <td>{{$i}}</td>
                   <td>{{$item->title}}</td>
                   <td>{{$item->active ? 'Active' :'Inactive'}}</td>
                   <td>{{web_date_in_timezone($item->created_at,'d-M-Y h:i A')}}</td>
                   <td class="text-center">

                   <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-dots-horizontal-rounded"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                    @if(get_user_permission('admin_users','u'))
                                    <a class="dropdown-item" href="{{url('admin/faq/edit/'.$item->id)}}"><i class="flaticon-pencil-1"></i> Edit</a>
                                    @endif
                                    
                                    
                                   
                                    @if(get_user_permission('admin_users','d'))
                                    <a class="dropdown-item" data-role="unlink"
                                    data-message="Do you want to remove this FAQ?"
                                    href="{{ url('admin/faq/delete/' . $item->id) }}"><i
                                        class="flaticon-delete-1"></i> Delete</a>
                                    @endif
                                    
                                </div>
                            </div>
                            
                        </td>
               </tr>
            @endforeach
            </tbody>
        </table>
       
            
    </div>
    </div>
</div>
@stop

@section("script")
<script src="{{asset('')}}admin_assets/plugins/table/datatable/datatables.js"></script>
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
@stop