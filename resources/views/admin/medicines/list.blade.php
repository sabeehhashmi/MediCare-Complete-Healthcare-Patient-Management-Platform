{{-- resources/views/admin/medicines/list.blade.php --}}
@extends('admin.template.layout')

@section('content')
<div class="card mb-5">
    @if(get_user_permission('medicines','c'))
    <div class="card-header">
        <a href="{{route('admin.medicines.create')}}" class="btn btn-primary waves-effect waves-light mb-2 me-2">
            <i class="mdi mdi-plus me-1"></i> Create Medicine
        </a>
    </div>
    @endif
    
    <div class="card-body overflow-hidden">
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Title (EN)</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Featured</th>
                        <th>Status</th>
                        <th>Created on</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $medicine)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>
                            @if($medicine->image)
                                <img src="{{ $medicine->image_url }}" alt="{{ $medicine->title_en }}" style="max-width: 50px; max-height: 50px; border-radius: 5px;">
                            @else
                                <span class="badge bg-secondary">No Image</span>
                            @endif
                        </td>
                        <td>{{ $medicine->title_en }}</td>
                        <td>{{ $medicine->category->title ?? 'N/A' }}</td>
                        <td>
                            @if($medicine->discount_price)
                                <span class="text-decoration-line-through text-muted"><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($medicine->price, 2) }}</span><br>
                                <span class="text-success fw-bold"><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($medicine->discount_price, 2) }}</span>
                            @else
                                <img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($medicine->price, 2) }}
                            @endif
                        </td>
                        <td>
                            @if($medicine->stock_quantity > 0)
                                <span class="badge bg-success">{{ $medicine->stock_quantity }}</span>
                            @else
                                <span class="badge bg-danger">Out of Stock</span>
                            @endif
                        </td>
                        <td>
                            <div class="form-check form-switch form-switch-lg mb-3" dir="ltr">
                                <input type="checkbox" class="form-check-input toggle-featured" 
                                       data-id="{{ $medicine->id }}"
                                       data-url="{{ url('admin/medicines/toggle_featured') }}"
                                       @if($medicine->featured) checked @endif>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-switch form-switch-lg mb-3" dir="ltr">
                                <input type="checkbox" class="form-check-input change_status" 
                                       data-id="{{ $medicine->id }}"
                                       data-url="{{ url('admin/medicines/change_status') }}"
                                       @if($medicine->status) checked @endif>
                            </div>
                        </td>
                        <td>{{ get_date_in_timezone($medicine->created_at,'d/m/Y h:i a' )}}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @if(get_user_permission('medicines', 'u'))
                                    <a class="dropdown-item" href="{{ route('admin.medicines.edit', ['id' => encrypt($medicine->id)]) }}">
                                        <i class="flaticon-pencil-1"></i> Edit
                                    </a>
                                    @endif
                                    
                                    @if(get_user_permission('medicines', 'r'))
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewMedicineModal{{$medicine->id}}">
                                        <i class="flaticon-eye"></i> View
                                    </a>
                                    @endif
                                    
                                    @if(get_user_permission('medicines', 'd'))
                                    <a class="dropdown-item" data-role="unlink"
                                       data-message="Do you want to remove this medicine? This may be linked with other sections"
                                       href="{{ route('admin.medicines.delete', ['id' => encrypt($medicine->id)]) }}">
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
@foreach($list as $medicine)
<div class="modal fade" id="viewMedicineModal{{$medicine->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Medicine Details - {{ $medicine->title_en }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="{{ $medicine->image_url }}" alt="{{ $medicine->title_en }}" class="img-fluid rounded mb-3" style="max-height: 150px;">
                        
                        @php
                            $galleryImages = is_array($medicine->gallery_images) 
                                ? $medicine->gallery_images 
                                : (json_decode($medicine->gallery_images, true) ?? []);
                        @endphp

                        @if(!empty($galleryImages))
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                @foreach($galleryImages as $image)
                                    @if(!empty($image))
                                        <img src="{{ get_uploaded_image_url($image, 'medicine_image_upload_dir') }}" 
                                            alt="Gallery" 
                                            style="width: 50px; height: 50px; border-radius: 5px; object-fit: cover;">
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th style="width: 35%;">Title (AR)</th>
                                <td style="direction: rtl; text-align: right;">{{ $medicine->title_ar ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Title (BN)</th>
                                <td>{{ $medicine->title_bn ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>SKU</th>
                                <td>{{ $medicine->sku ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ $medicine->category->title ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Manufacturer</th>
                                <td>{{ $medicine->manufacturer ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Prescription Required</th>
                                <td>{{ $medicine->prescription_required ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <th>Tags</th>
                                <td>
                                    @if($medicine->productTags->count() > 0)
                                        @foreach($medicine->productTags as $tag)
                                        <span class="badge me-1" style="background-color: {{ $tag->color }}; color: white;">{{ $tag->name_en }}</span>
                                        @endforeach
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-12 mt-3">
                        <ul class="nav nav-tabs" id="medicineTab{{$medicine->id}}" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="desc-tab{{$medicine->id}}" data-bs-toggle="tab" data-bs-target="#desc{{$medicine->id}}" type="button">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="uses-tab{{$medicine->id}}" data-bs-toggle="tab" data-bs-target="#uses{{$medicine->id}}" type="button">Uses</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="benefits-tab{{$medicine->id}}" data-bs-toggle="tab" data-bs-target="#benefits{{$medicine->id}}" type="button">Benefits</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="side-effects-tab{{$medicine->id}}" data-bs-toggle="tab" data-bs-target="#side-effects{{$medicine->id}}" type="button">Side Effects</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="how-to-use-tab{{$medicine->id}}" data-bs-toggle="tab" data-bs-target="#how-to-use{{$medicine->id}}" type="button">How to Use</button>
                            </li>
                        </ul>
                        
                        <div class="tab-content p-3 border border-top-0 rounded-bottom">
                            <div class="tab-pane fade show active" id="desc{{$medicine->id}}">
                                <h6>Description:</h6>
                                <p>{!!$medicine->description ?? 'N/A' !!}</p>
                                @if($medicine->short_description)
                                <h6 class="mt-2">Short Description:</h6>
                                <p>{!! $medicine->short_description !!}</p>
                                @endif
                            </div>
                            
                            <div class="tab-pane fade" id="uses{{$medicine->id}}">
                                <p>{!! $medicine->uses ?? 'N/A' !!}</p>
                            </div>
                            
                            <div class="tab-pane fade" id="benefits{{$medicine->id}}">
                                <p>{!! $medicine->benefits ?? 'N/A' !!}</p>
                            </div>
                            
                            <div class="tab-pane fade" id="side-effects{{$medicine->id}}">
                                <p>{!! $medicine->side_effects ?? 'N/A' !!}</p>
                            </div>
                            
                            <div class="tab-pane fade" id="how-to-use{{$medicine->id}}">
                                <p>{!! $medicine->how_to_use ?? 'N/A' !!}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($medicine->other_info)
                    <div class="col-12 mt-3">
                        <h6>Other Information:</h6>
                        <p>{{ $medicine->other_info }}</p>
                    </div>
                    @endif
                    
                    @if($medicine->meta_title || $medicine->meta_description || $medicine->meta_keywords)
                    <div class="col-12 mt-3">
                        <h6>SEO Information:</h6>
                        <table class="table table-sm table-bordered">
                            @if($medicine->meta_title)
                            <tr>
                                <th style="width: 20%;">Meta Title</th>
                                <td>{{ $medicine->meta_title }}</td>
                            </tr>
                            @endif
                            @if($medicine->meta_description)
                            <tr>
                                <th>Meta Description</th>
                                <td>{{ $medicine->meta_description }}</td>
                            </tr>
                            @endif
                            @if($medicine->meta_keywords)
                            <tr>
                                <th>Meta Keywords</th>
                                <td>{{ $medicine->meta_keywords }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script>
jQuery(document).ready(function(){
    App.initTreeView();
    
    // Toggle Featured Status
    $('.toggle-featured').on('change', function() {
        let checkbox = $(this);
        let id = checkbox.data('id');
        let url = checkbox.data('url');
        let status = checkbox.prop('checked') ? 1 : 0;
        
        $.ajax({
            type: "POST",
            url: url,
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                featured: status
            },
            success: function(res) {
                if (res.status == '1') {
                    App.alert(res.message || 'Featured status updated', 'Success!', 'success');
                } else {
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    App.alert(res.message || 'Failed to update featured status', 'Oops!', 'error');
                }
            },
            error: function() {
                checkbox.prop('checked', !checkbox.prop('checked'));
                App.alert('Network error please try again', 'Oops!', 'error');
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    // Disable autocomplete on all inputs immediately
    document.querySelectorAll("form").forEach(function(form) {
        form.setAttribute("autocomplete", "off");
    });

    document.querySelectorAll("input").forEach(function(input) {
        input.setAttribute("autocomplete", "new-password");
    });

    // For DataTables dynamic fields
    $(document).on('focus', 'input', function() {
        $(this).attr('autocomplete', 'off');
    });
});
</script>
@stop