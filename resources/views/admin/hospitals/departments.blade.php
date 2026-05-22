@extends('admin.template.layout')

@section('content')

<div class="card mb-5">
  @if(get_user_permission('departments','c'))

  <div class="card-header">
      <div class="col-lg-12">
        <a  href=" {{route('admin.hospitals.index')}}"  class="btn btn-primary float-end ms-3">Back</a>
        <a href="{{route('admin.hospitals.createDepartment', $hospital_id)}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i
        class="mdi mdi-plus me-1"></i> Add Department</a>
    </div>
  </div>
  @endif
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-condensed table-striped" id="example22">
        <thead>
          <tr>
            <th data-colname="id">#</th>
            <th data-colname="title">Department Name</th>
            <th data-colname="title_ar">Department Manager</th>
            
            <th data-colname="status">Email</th>
           
            <th data-colname="action">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($department_list as $row)
          <tr>
            <td>{{ $loop->index + 1 + ($department_list->perPage() * ($department_list->currentPage() - 1)) }}</td>
            <td>{{ $row->department->title }}</td>
            <td>{{ $row->manager_name?$row->manager_name: 'NA' }}</td>
            <td>{{ $row->email?$row->email: 'NA' }}</td>
            
            
            <td>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-chevron-down"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @if (get_user_permission('departments', 'u'))
                      <a class="dropdown-item" href="{{ route('admin.hospitals.editDepartment', ['hospital_id' => $hospital_id, 'id' => $row->id]) }}">
                        <i class="flaticon-pencil-1"></i> Edit Department
                      </a>
                      @endif

                      @if (get_user_permission('departments', 'd'))
                      <a class="dropdown-item" data-role="unlink"
                        data-message="Do you want to remove the department?  This may be linked with other sections"
                        href="{{ route('admin.hospitals.deleteDepartment', ['id' => encrypt($row->id)]) }}">
                        <i class="flaticon-delete-1"></i> Delete Department
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
          {!! $department_list->appends(request()->all())->links('admin.template.pagination') !!}
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