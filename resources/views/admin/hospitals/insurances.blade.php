@extends('admin.template.layout')

@section('content')

<div class="card mb-5">
  @if(get_user_permission('insurence_policy','c'))

  <div class="card-header">
    <a href="{{route('admin.hospitals.createInsurance', $hospital_id)}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i
        class="mdi mdi-plus me-1"></i> Create Hospital Insurance</a>
  </div>
  @endif
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-condensed table-striped" id="example29">
        <thead>
          <tr>
            <th data-colname="id">#</th>
            <th data-colname="title">Insurance</th>
            <th data-colname="title_ar">Sub Insurance</th>
            <th data-colname="action">Action</th>
          </tr>
        </thead>
        <tbody>
          @if($list->count() == 0)
              <tr>
                  <td colspan="4">No Data Available</td>
              </tr>
          @endif
          @foreach ($list as $row)
          <tr>
            <td>{{ $loop->index + 1 + ($list->perPage() * ($list->currentPage() - 1)) }}</td>
            <td>{{ $row->insurance->title ?? null }}</td>
            <td>{{ $row->subInsurance->title ?? null }}</td>
            
            <td>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-chevron-down"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      @if (get_user_permission('hospitals', 'd'))
                      <a class="dropdown-item" data-role="unlink"
                        data-message="Do you want to remove the hospital Insurance?  This may be linked with other sections"
                        href="{{ route('admin.hospitals.deleteInsurance', ['id' => encrypt($row->id)]) }}">
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