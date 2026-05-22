@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin_assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin_assets/plugins/table/datatable/custom_dt_customer.css">
@stop


@section("content")
<div class="card mb-5">
    <div class="card-body">
    <div class="dataTables_wrapper container-fluid dt-bootstrap4">
    @if($list->count() > 0)

    <div class="row">
        <!-- <div class="col-sm-12 col-md-6">
            <div class="dataTables_length" id="column-filter_length">
            </div>
        </div> -->

        <form method="get" action='' class="col-sm-12 col-md-8">
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
                <th>Contact Info</th>
                <th>Message</th>
                <th>Status</th>
                <th>Created Date</th>
{{--                <th>Action</th>--}}
                </tr>
            </thead>
            <tbody>
<!--            --><?php //$i = $list->perPage() * ($list->currentPage() - 1); ?>
            @foreach($list as $item)
<!--            --><?php //$i++; ?>
               <tr>
                   <td>{{$loop->iteration}}</td>
                   <td>
                       <a href="#" class="yellow-color">{{$item->name}}</a>
                       <div class="">
                            {{$item->email}} <br>
                            +{{$item->mobile_number}}
                        </div>
                   </td>
                   <td>{{$item->message}}</td>
                   <td>
                       <select class="form-control">
                           <option>
                               Pending
                           </option>
                           <option>
                               Replied
                           </option>
                           <option>
                               Resolved
                           </option>
                       </select>
                   <td>{{web_date_in_timezone($item->created_at,'d-M-Y h:i A')}}</td>
               </tr>
            @endforeach
            </tbody>
        </table>

            <div class="mt-4">
                <div class="col-sm-12 col-md-12 pull-right">
                    <span>
                        Showing 
                        {{
                            (($datamain->currentPage() - 1) * $datamain->perPage()) + 1
                        }} 
                        to 
                        {{
                            min($datamain->currentPage() * $datamain->perPage(), $datamain->total())
                        }} 
                        of {{$datamain->total()}} entries
                    </span>
                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                        {!! $datamain->appends(request()->input())->links('admin.template.pagination') !!}
                    </div>
                </div>
            </div>
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
