@extends('admin.template.layout')

@section('content')

<div class="card mb-5">
  @if(get_user_permission('languages','c'))

  <div class="card-header">
    <a href="{{route('admin.languages.create')}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i
        class="mdi mdi-plus me-1"></i> Create</a>
  </div>
  @endif
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-condensed table-striped" id="example2">
        <thead>
          <tr>
            <th data-colname="id">#</th>
            <th data-colname="title">Title</th>
            
            <th data-colname="created_at">Created on</th>
            <th data-colname="status">Status</th>
            <th data-colname="action">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($list as $role)
          <tr>
            <td>{{ $loop->index+1}}</td>
            <td>{{ $role->title }}</td>
            <td>{{ get_date_in_timezone($role->created_at,'d/m/Y h:i a' )}}</td>
            <td>
                <div class="form-check form-switch form-switch-lg mb-3" dir="ltr">
                    <input type="checkbox" class="form-check-input change_status" data-id="{{ $role->id }}"
                                data-url="{{ url('admin/languages/change_status') }}"
                                @if ($role->status) checked @endif>
                </div>
            </td>
            
            <td>
              <div class="dropdown">
                  <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-dots-horizontal-rounded"></i>
                  </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @if (get_user_permission('languages', 'u'))
                      <a class="dropdown-item" href="{{ route('admin.languages.edit', ['id' => encrypt($role->id)]) }}">
                        <i class="flaticon-pencil-1"></i> Edit
                      </a>
                      @endif

                      @if (get_user_permission('languages', 'd'))
                      <a class="dropdown-item" data-role="unlink"
                        data-message="Do you want to remove the languages?  This may be linked with other sections"
                        href="{{ route('admin.languages.delete', ['id' => encrypt($role->id)]) }}">
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