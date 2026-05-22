@include('agent.layouts.header')
<div class="mb-5 position-relative">
    <div class="card mb-5">
        <div class="position-relative">
            <div class="card-header card-header d-flex justify-content-between">
                <a href="{{route('agent.createDepartment')}}" class="btn btn-primary w-auto ml-auto"> Add Department</a>
            </div>
            <div class="card-body">
                <div class="table-wrap" id="tableDiv">
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped" id="table_list">
                        <thead>
                        <tr>
                            <th data-colname="id">#</th>
                            <th data-colname="title">Department Name</th>
                            <th data-colname="title_ar">Department Manager</th>
                            
                            <th data-colname="status">Email</th>
                            <th data-colname="created_at">Phone Number</th>
                            <th data-colname="action">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($department_list as $row)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $row->department->title }}</td>
                            <td>{{ $row->manager_name }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->dial_code ? '+'.$row->dial_code : '' }} {{ $row->phone }}</td>
                            
                            <td>
                            <div class="dropdown mt-4 mt-sm-0">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{route('agent.editDepartment',['hospital_id' => $hospital_id, 'id' => encrypt($row->id)])}}">Edit Department</a>
                                    <a class="dropdown-item delete" data-role="unlink"
                                    data-message="Do you want to remove the hospital departments?  This may be linked with other sections"
                                    href="{{ route('agent.deleteDepartment', ['id' => encrypt($row->id)]) }}" data-id="{{encrypt($row->id)}}">
                                    <i class="flaticon-delete-1"></i> Delete Department</a>
                                    </a>
                                </div>
                            </div>
                            <!-- <div class="dropdown">
                                
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="{{ route('agent.editDepartment', ['hospital_id' => $hospital_id, 'id' => encrypt($row->id)]) }}">
                                        <i class="flaticon-pencil-1"></i> Edit
                                        </a>
                                    <a class="dropdown-item delete" data-role="unlink"
                                    data-message="Do you want to remove the hospital departments?  This may be linked with other sections"
                                    href="{{ route('agent.deleteDepartment', ['id' => encrypt($row->id)]) }}" data-id="{{encrypt($row->id)}}">
                                    <i class="flaticon-delete-1"></i> Delete</a>
                                </div>
                            </div> -->
                                
                                
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-right">
                        {{ $department_list->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('agent.layouts.footer')
<script>
    $("#base-style").DataTable();
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.delete').on('click', function(e) {
            var deleteDepartmentUrl = "{{ route('agent.deleteDepartment', ['id' => ':id']) }}";
            e.preventDefault();
            var departmentId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send AJAX request to delete department
                    $.ajax({
                        url: deleteDepartmentUrl.replace(':id', departmentId),
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == '1') {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                );
                                setTimeout(function() {
                                    location.reload(); // Reload the page
                                }, 3000);
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            Swal.fire(
                                'Error!',
                                'Failed to delete department.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>