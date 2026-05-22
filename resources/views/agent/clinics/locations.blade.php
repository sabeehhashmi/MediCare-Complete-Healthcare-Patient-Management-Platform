@include('agent.layouts.header')
<div class="card mb-5">
  

  <div class="card-header">
    <a href="{{route('agent.hospitals.createLocation', $hospital_id)}}" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2 "><i
        class="mdi mdi-plus me-1"></i> Create Hospital Location</a>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-condensed table-striped" id="example2">
        <thead>
          <tr>
            <th data-colname="id">#</th>
            <th data-colname="title">Location</th>
            <th data-colname="title_ar">Latitude</th>
            <th data-colname="title_ar">Longitude</th>
            <th data-colname="action">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($list as $row)
          <tr>
            <td>{{ $loop->index + 1 + ($list->perPage() * ($list->currentPage() - 1)) }}</td>
            <td><a href="{{ $row->location ?? null }}" target="_blank">View Location</a></td>
            <td>{{ $row->latitude ?? null }}</td>
            <td>{{ $row->longitude ?? null }}</td>
            
            <td>
            <div class="dropdown">
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bx bx-dots-horizontal-rounded"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                   
                      <a class="dropdown-item" href="{{ route('agent.hospitals.editLocation', ['hospital_id' => $hospital_id, 'id' => encrypt($row->id)]) }}">
                        <i class="flaticon-pencil-1"></i> Edit
                      </a>
                  
                      <a class="dropdown-item" data-role="unlink"
                        data-message="Do you want to remove the hospital Location?  This may be linked with other sections"
                        href="{{ route('agent.hospitals.deleteLocation', ['id' => encrypt($row->id)]) }}">
                        <i class="flaticon-delete-1"></i> Delete
                      </a>
                    
                </div>
            </div>
                  
                
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <div class="col-sm-12 col-md-12 pull-right">
        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
          {!! $list->appends(request()->all())->links('agent.template.pagination') !!}
        </div>
      </div>

    </div>
  </div>
</div>
@include('agent.layouts.footer')
<script>
  jQuery(document).ready(function(){

      App.initTreeView();

  })
</script>
