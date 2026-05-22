
@include('agent.layouts.header')
<div class="card mb-5">
  

<div class="card-header card-header d-flex justify-content-between">
            <a href="{{route('agent.clinics.createInsurance', $hospital_id)}}" class="btn btn-primary w-auto ml-auto">  Create Clinic Insurance</a>
</div>


 
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-condensed table-striped" id="example2">
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
                <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bx bx-dots-horizontal-rounded"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    
                      <a class="dropdown-item" data-role="unlink"
                        data-message="Do you want to remove the clinic Insurance?  This may be linked with other sections"
                        href="{{ route('agent.clinics.deleteInsurance', ['id' => encrypt($row->id)]) }}">
                        <i class="flaticon-delete-1"></i> Delete
                      </a>
                   
                </div>
            </div>
                  
                
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="mt-4">
                        <div class="col-sm-12 col-md-12 pull-right">
                            <span>
                                Showing 
                                {{
                                    (($list->currentPage() - 1) * $list->perPage()) + 1
                                }} 
                                to 
                                {{
                                    min($list->currentPage() * $list->perPage(), $list->total())
                                }} 
                                of {{$list->total()}} entries
                            </span>
                            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                                {!! $list->appends(request()->input())->links('admin.template.pagination') !!}
                            </div>
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

 <script>
    $(document).ready(function() {
        // Select2 initialization (if needed)
        $('[role="select2"]').select2();

        // Handle record delete
        $('body').on('click', '[data-role="unlink"]', function(e) {
            e.preventDefault();
            var msg = $(this).data('message') || 'Are you sure that you want to delete this record?';
            var href = $(this).attr('href');

            App.confirm('Confirm Delete', msg, function() {
                // Perform AJAX delete request
                $.ajax({
                    url: href,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        "_token": "{{ csrf_token() }}", // Ensure this matches your Laravel setup
                    },
                    success: function(res) {
                        if (res.status == 1) {
                            App.alert(res.message || 'Deleted successfully', 'Success!');
                            setTimeout(function() {
                                window.location.reload(); // Refresh page after successful delete
                            }, 1500);
                        } else {
                            App.alert(res.message || 'Unable to delete the record.', 'Failed!');
                        }
                    },
                    error: function(jqXhr, textStatus, errorMessage) {
                        // Handle error
                    }
                });
            });


        });
    });
</script>
