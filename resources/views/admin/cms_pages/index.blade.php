@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin_assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin_assets/plugins/table/datatable/custom_dt_customer.css">
@stop


@section("content")
<div class="card mb-5">


    @if (get_user_permission('cms','c'))
    <div class="card-header">
    {{--<a href="{{url('admin/page/create?type='.$type)}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i
    class="mdi mdi-plus me-1"></i>  Create Page</a>
   </div>--}}

    @endif
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-condensed table-striped" id="example2">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cms_pages as $key => $policy)
                <tr>
                    <td>{{ $key +1 }}</td>
                    <td>{{ $policy->title_en }}</td>
                    <td>{{ $policy->status == 1 ? "Active" : "In Active" }}</td>
                    <td>{{ date('d-m-Y ',strtotime($policy->created_at)) }}</td>
                    
                    <td class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-dots-horizontal-rounded"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @if(get_user_permission('cms','u'))
                                        <a class="dropdown-item" href="{{ route('admin.cms_pages.edit', ['id' => $policy->id,'type'=>$type])}}"><i class="flaticon-pencil-1"></i>Edit</a>
                                        @endif
                                        
                                        @if(get_user_permission('cms','d'))
                                       {{-- <a class="dropdown-item" data-role="unlink"
                                        data-message="Do you want to remove this page?"
                                        href="{{ url('admin/page/delete/' . $policy->id) }}"><i
                                            class="flaticon-delete-1"></i> Delete</a> --}}

                                            @endif 
                        </div>
                     
                    </td>
                </tr>                                    
            @endforeach    
            </tbody>
        </table>

            <div class="mt-4">
              
            </div>
        </div>
    </div>
</div>

@stop

@section("script")
<script src="{{asset('')}}admin_assets/plugins/table/datatable/datatables.js"></script>
<script>
$('#example2123').DataTable({
      "paging": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });

@stop