{{-- resources/views/admin/product_tags/list.blade.php --}}
@extends('admin.template.layout')

@section('content')
<div class="card mb-5">
    @if(get_user_permission('product_tags','c'))
    <div class="card-header">
        <a href="{{route('admin.product_tags.create')}}" class="btn btn-primary waves-effect waves-light mb-2 me-2">
            <i class="mdi mdi-plus me-1"></i> Create Product Tag
        </a>
    </div>
    @endif
    
    <div class="card-body overflow-hidden">
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Color</th>
                        <th>Name (EN)</th>
                        <th>Name (AR)</th>
                        <th>Name (BN)</th>
                        <th>Slug</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Created on</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $tag)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>
                            <span style="display: inline-block; width: 30px; height: 30px; border-radius: 50%; background-color: {{ $tag->color }}; border: 2px solid #ddd;"></span>
                        </td>
                        <td>{{ $tag->name_en }}</td>
                        <td style="direction: rtl; text-align: right;">{{ $tag->name_ar }}</td>
                        <td>{{ $tag->name_bn }}</td>
                        <td><code>{{ $tag->slug }}</code></td>
                        <td>
                            <span class="badge bg-info">{{ $tag->medicines->count() }}</span>
                        </td>
                        <td>
                            <div class="form-check form-switch form-switch-lg mb-3" dir="ltr">
                                <input type="checkbox" class="form-check-input change_status" 
                                       data-id="{{ $tag->id }}"
                                       data-url="{{ url('admin/product-tags/change_status') }}"
                                       @if($tag->status) checked @endif>
                            </div>
                        </td>
                        <td>{{ get_date_in_timezone($tag->created_at,'d/m/Y h:i a' )}}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @if(get_user_permission('product_tags', 'u'))
                                    <a class="dropdown-item" href="{{ route('admin.product_tags.edit', ['id' => encrypt($tag->id)]) }}">
                                        <i class="flaticon-pencil-1"></i> Edit
                                    </a>
                                    @endif
                                    
                                    @if(get_user_permission('product_tags', 'r'))
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewTagModal{{$tag->id}}">
                                        <i class="flaticon-eye"></i> View
                                    </a>
                                    @endif
                                    
                                    @if(get_user_permission('product_tags', 'd'))
                                    <a class="dropdown-item" data-role="unlink"
                                       data-message="Do you want to remove this tag? This will be removed from all associated medicines."
                                       href="{{ route('admin.product_tags.delete', ['id' => encrypt($tag->id)]) }}">
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

{{-- View Details Modals --}}
@foreach($list as $tag)
<div class="modal fade" id="viewTagModal{{$tag->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tag Details - {{ $tag->name_en }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 35%;">Color</th>
                        <td>
                            <span style="display: inline-block; width: 30px; height: 30px; border-radius: 50%; background-color: {{ $tag->color }}; border: 2px solid #ddd; vertical-align: middle;"></span>
                            <span class="ms-2">{{ $tag->color }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Name (English)</th>
                        <td>{{ $tag->name_en }}</td>
                    </tr>
                    <tr>
                        <th>Name (Arabic)</th>
                        <td style="direction: rtl; text-align: right;">{{ $tag->name_ar }}</td>
                    </tr>
                    <tr>
                        <th>Name (Bengali)</th>
                        <td>{{ $tag->name_bn }}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td><code>{{ $tag->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $tag->description ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($tag->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Associated Medicines</th>
                        <td>
                            @if($tag->medicines->count() > 0)
                                <div style="max-height: 200px; overflow-y: auto;">
                                    <ul class="list-group">
                                        @foreach($tag->medicines as $medicine)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $medicine->title_en }}
                                            <span class="badge bg-primary rounded-pill">${{ number_format($medicine->price, 2) }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <span class="text-muted">No medicines associated</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created By</th>
                        <td>{{ $tag->created_by ?? 'System' }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated</th>
                        <td>{{ get_date_in_timezone($tag->updated_at,'d/m/Y h:i a' )}}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                @if(get_user_permission('product_tags', 'u'))
                <a href="{{ route('admin.product_tags.edit', ['id' => encrypt($tag->id)]) }}" class="btn btn-primary">
                    <i class="flaticon-pencil-1"></i> Edit Tag
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('script')
<script>
jQuery(document).ready(function(){
    App.initTreeView();
});
</script>
@stop