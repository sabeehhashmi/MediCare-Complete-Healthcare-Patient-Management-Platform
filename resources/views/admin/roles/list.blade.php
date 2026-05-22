@extends('admin.template.layout')

@section('content')

<div class="card mb-5">
  @if(get_user_permission('user_roles','c'))

  <div class="card-header">
    <a href="{{route('admin.user_roles.create')}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i
        class="mdi mdi-plus me-1"></i> Create</a>
  </div>
  @endif
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-condensed table-striped" id="example2">
        <thead>
          <tr>
            <th data-colname="id">#</th>
            <th data-colname="role">Role Name</th>
            
            <th data-colname="created_at">Created on</th>
            <th data-colname="status">Status</th>
            <th data-colname="action">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($roles as $role)
          <tr>
            <td>{{ $loop->index + 1 + ($roles->perPage() * ($roles->currentPage() - 1)) }}</td>
            <td>{{ $role->role }}</td>
            <td>{{ $role->created_at }}</td>
            <td>{{ $role->getStatusText() }}</td>
            
            <td>
            <div class="dropdown">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bx bx-dots-horizontal-rounded"></i>
                </button>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @if (get_user_permission('user_roles', 'u'))
                      <a class="dropdown-item" href="{{ route('admin.user_roles.edit', ['id' => encrypt($role->id)]) }}">
                        <i class="flaticon-pencil-1"></i> Edit
                      </a>
                      @endif

                      @if (get_user_permission('user_roles', 'd'))
                      <a class="dropdown-item" data-role="unlink"
                        data-message="Do you want to remove the role? Make sure all users will be removed related to this role!"
                        href="{{ route('admin.user_roles.delete', ['id' => encrypt($role->id)]) }}">
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
          {!! $roles->appends(request()->all())->links('admin.template.pagination') !!}
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