@extends('admin.template.layout')

@section('content')

<div class="card mb-5">
 

  <div class="card-header">
  @if(get_user_permission('products','c'))
    <!-- <a href="{{route('admin.create_product')}}" class="btn-custom btn mr-2 mt-2 mb-2"><i
        class="fa-solid fa-plus"></i> Create</a> -->
        @endif
        <div class="col-md-12">
          <form method="get" action="">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <input class="form-control" type="text" name="search_key" value="{{$_GET['search_key']??''}}" placeholder="Ticket Name">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <input class="form-control" type="text" name="from_date" data-provide="datepicker" value="{{$_GET['from_date']??''}}" placeholder="From Date">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <input class="form-control" type="text" name="to_date" data-provide="datepicker" value="{{$_GET['to_date']??''}}" placeholder="To Date">
                </div>
              </div>
              
              <div class="from-group">
                <button type="submit" class="btn btn-primary">FIlter</button>
                <a href="{{route('admin.list_product')}}" class="btn btn-success">Clear</a>
              </div>
            </div>
          </form>
        </div>
  </div>
  
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-condensed table-striped" id="example2">
        <thead>
          <tr>
            <th data-colname="id">#</th>
            <th data-colname="file_url">Ticket </th>
            <th data-colname="product_name">Ticket Name</th>
            <th data-colname="product_type">Type</th>
            <th data-colname="price">Price</th>
            <th data-colname="status">Status</th>
            <th data-colname="created_at">Created on</th>
            <th data-colname="action">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($list as $role)
          <tr>
            <td>{{ $loop->index + 1 + ($list->perPage() * ($list->currentPage() - 1)) }}</td>
            <td><img src="{{ $role->file_url }}" width="80" height="70"></td>
            <td>{{ $role->product_name }}</td>
            <td>{{ $role->product_type }}</td>
            <td>{{ $role->price }}</td>
            <td>{{ $role->getStatusText() }}</td>
            <td>{{ web_date_in_timezone($role->created_at) }}</td>
            <td>
              <div class="dropdown">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bx bx-dots-horizontal-rounded"></i>
                </button>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  @if (get_user_permission('products', 'u'))
                  <a class="dropdown-item" href="{{ route('admin.edit_product', ['id' => $role->id]) }}">
                    <i class="flaticon-pencil-1"></i> Edit
                  </a>
                  @endif

                  @if (get_user_permission('products', 'd'))
                  <a class="dropdown-item" data-role="unlink"
                    data-message="Do you want to remove the product? "
                    href="{{ route('admin.delete_product', ['id' => $role->id]) }}">
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

      <div class="col-sm-12 col-md-12 pull-right">
        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
          {!! $list->appends(request()->all())->links('admin.template.pagination') !!}
        </div>
      </div>

    </div>
  </div>
</div>
@stop
@section('script')
<script>
  jQuery(document).ready(function(){

      App.initTreeView();

  })
</script>
@stop