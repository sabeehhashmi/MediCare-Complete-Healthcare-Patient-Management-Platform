@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/plugins/table/datatable/datatables.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/plugins/table/datatable/custom_dt_customer.css') }}">
@stop

@section("content")

    @if ( session('success'))
        <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>{{ session('success') }}</strong>
        </div>
    @endif

    @if ( session('error'))
        <div class="alert alert-danger alert-dismissable custom-danger-box" style="margin: 15px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>{{ session('error') }}</strong>
        </div>
    @endif

    <div class="card mb-5">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-condensed table-striped" id="table_list">
                    <thead>
                        <tr>
                            <th>Sr#</th>
                            <th>Patient Name</th>
                            <th>Phone No</th>
                            <th>Doctor Name</th>
                            <th>Rating</th>
                            <th>Feedback</th> 
                            <th>Status</th> 
                            <th>Date & Time</th> 
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $key => $review)
                            <tr>
                                <td>{{ ($reviews->currentPage() - 1) * $reviews->perPage() + $key + 1 }}</td>
                                <td>{{ $review->user->name }}</td> 
                                <td>{{ '+'. $review->user->dial_code . $review->user->phone }}</td> 
                                <td> 
                                    @if($review->doctor && $review->doctor->user)
                                        {{ $review->doctor->user->name }}
                                    @else
                                        No doctor found
                                    @endif
                                </td>
                                 
                                <td>{{ $review->rating }}</td> 
                                <td>{{ Str::limit($review->feeback_message, 10) }}</td>
                                <td><div class="form-check form-switch form-switch-lg mb-3" dir="ltr">
                        <input type="checkbox" class="form-check-input change_status" data-id="{{$review->id }}"
                                    data-url="{{  url('admin/change_review_status') }}"
                                    {{  ($review->status == 1 ? 'checked' : '') }}></td>
                                <td>{{ $review->created_at }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton_{{ $key }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bx bx-dots-horizontal-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $key }}">
                                            <a class="dropdown-item view" href="#" data-bs-toggle="modal" data-bs-target="#view_{{ $key }}">View</a>
                                            @if(get_user_permission('settings','u'))
                                                <a class="dropdown-item" href="{{ route('admin.reviews.edit', $review->id) }}"><i class="flaticon-pencil-1"></i> Edit</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal for each review -->
                            <div class="modal fade" id="view_{{ $key }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fs-5" id="exampleModalLabel">Review Detail</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Patient Name: {{ $review->user->name }}</h6>
                                            <p>Phone No: {{ '+'. $review->user->dial_code . $review->user->phone }}</p>
                                            <h6>Doctor Name: 
                                                @if($review->doctor && $review->doctor->user)
                                                    {{ $review->doctor->user->name }}
                                                @else
                                                    No doctor found
                                                @endif
                                            </h6>
                                            <h6>Hospital Name: 
                                                @if($review->hospital && $review->hospital->user)
                                                    {{ $review->hospital->user->name }}
                                                @else
                                                    No hospital found
                                                @endif
                                            </h6>
                                            <p>Rating: {{ $review->rating }}</p>
                                            <p>Feedback: {{ $review->feeback_message }}</p>
                                            <p>Date & Time: {{ $review->created_at }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    <div class="col-sm-12 col-md-12 pull-right">
                        <span>
                            Showing 
                            {{
                                (($reviews->currentPage() - 1) * $reviews->perPage()) + 1
                            }} 
                            to 
                            {{
                                min($reviews->currentPage() * $reviews->perPage(), $reviews->total())
                            }} 
                            of {{$reviews->total()}} entries
                        </span>
                        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                            {!! $reviews->appends(request()->input())->links('admin.template.pagination') !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@stop

@section("page_script")
    <script src="{{ asset('admin-assets/assets/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/js/dataTables.bootstrap5.min.js') }}"></script>
@stop