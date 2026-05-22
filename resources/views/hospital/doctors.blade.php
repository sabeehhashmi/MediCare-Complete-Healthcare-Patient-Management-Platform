@include('hospital.layouts.header')
<div class="position-relative mb-5">
<div class="card mb-5">

        <div class="card-header card-header d-flex justify-content-between">
            <a href="{{ route('hospital.createDoctor') }}" class="btn btn-primary w-auto ml-auto"> Add Doctor</a>
        </div>
        <div class="card-body">
        <!-- <input type="hidden" value="{{$hospital_id ?? null}}" id="hospital_id" name="hospital_id"> -->
            <div class="table-wrap" id="tableDiv">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped" id="table_list">
                        <thead>
                            <tr>
                            <th>#</th>
                            <th>Doctor Name</th>
                            <th>Email ID</th>
                            <th>Phone Number</th>
                            <th>Country</th>
                            <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>
                    </table>    
                </div>
            </div>
        </div>
    </div>
</div>
@include('hospital.layouts.footer')

<script>
    $(document).ready(function () {
        // var value = $("#hospital_id").val();
        $('#table_list').DataTable({
            processing: true,
            serverSide: true,
            filter: true,
            searching:true,
            ajax: {
                'type':'GET',
                'url' : '{{ route("hospital.load-data-doctors") }}',
                'data':{
                    '_token': '{{csrf_token()}}',
                    // 'hospital_id': value
                }
            },
            columns: [
                {data: 'sl_no'},
                {data: 'first_name'},
                {data: 'email'},
                {data: 'phone_number'},
                {data: 'country_name'},
                {data: 'action',  orderable: false, searchable: false}
            ],
            order: [],
            language: {
            loadingRecords: "No Data Available",
        },
        });

        // Implement search functionality
        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            $('#table_list').DataTable().search($(this).serialize()).draw();
        });
    });
</script>