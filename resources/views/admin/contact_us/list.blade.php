@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop

@section('content')

@section("content")
@if ( session('success'))
<div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session('success') }} </strong>
</div>
@endif
@if ( session('error'))
<div class="alert alert-danger alert-dismissable custom-danger-box" style="margin: 15px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong> {{ session('error') }} </strong>
</div>
@endif

<?php $permission_id = "contact_us_entries"; ?>
<div class="card mb-5">

    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-condensed table-striped" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th class="text-center">Action</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php $i = $entries->perPage() * ($entries->currentPage() - 1); ?>
                    @foreach ($entries as $entry)
                    <?php $i++; ?>
                    <tr>
                        <td>{{ $i }}</td>
                        
            
                        <td>
                        {{ucfirst($entry->name)}}
                        </td>

                        <td>{{$entry->email}}</td>
                        <td>{{$entry->subject}}</td>
                        <td>{{ substr($entry->message, 0, 50) }}</td>
                        <td>{{$entry->status}}</td>
                        <td>{{web_date_in_timezone($entry->created_at,'d-M-Y h:i A')}}</td>
                        <td class="text-center action">

                         <a href="{{ route('admin.contact-us-entries.show', ['contact_us'=> $entry->id]) }}" class="btn btn-icon btn-primary"><i class="fa fa-eye"></i></a>
                            
                            
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <span>Total {{ $entries->total() }} entries</span>
                <div class="col-sm-12 col-md-12 pull-right">
                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                        {!! $entries->appends(request()->input())->links('admin.template.pagination') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
<script src="{{ asset('') }}admin-assets/plugins/table/datatable/datatables.js"></script>
<script>
    App.initFormView();

    $('#example2').DataTable({
        "paging": false,
        "searching": false,
        "ordering": false,
        "info": false,
        "autoWidth": true,
        "responsive": true,
    });
</script>
@stop