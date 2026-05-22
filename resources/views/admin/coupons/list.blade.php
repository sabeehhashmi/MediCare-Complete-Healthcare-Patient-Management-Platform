{{-- resources/views/admin/coupons/list.blade.php --}}
@extends('admin.template.layout')

@section('content')
<div class="card mb-5">
    @if(get_user_permission('coupons','c'))
    <div class="card-header">
        <a href="{{route('admin.coupons.create')}}" class="btn btn-primary">
            <i class="mdi mdi-plus me-1"></i> Create Coupon
        </a>
    </div>
    @endif

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Title (EN)</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Usage</th>
                        <th>Validity</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $coupon)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $coupon->code }}</span>
                        </td>
                        <td>{{ $coupon->title_en }}</td>
                        <td>
                            @if($coupon->type == 'fixed')
                                <span class="badge bg-info">Fixed</span>
                            @else
                                <span class="badge bg-warning">Percentage</span>
                            @endif
                        </td>
                        <td>
                            @if($coupon->type == 'fixed')
                                <img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($coupon->value, 2) }}
                            @else
                                {{ $coupon->value }}% 
                                @if($coupon->max_discount)
                                    (Max <img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($coupon->max_discount, 2) }})
                                @endif
                            @endif
                        </td>
                        <td>
                            {{ $coupon->usages_count }} / {{ $coupon->total_uses ?? '∞' }}
                            <br>
                            <small>{{ $coupon->per_user_uses }} per user</small>
                        </td>
                        <td>
                            @if($coupon->start_date || $coupon->end_date)
                                @if($coupon->start_date)
                                    From: {{ date('d/m/Y', strtotime($coupon->start_date)) }}<br>
                                @endif
                                @if($coupon->end_date)
                                    To: {{ date('d/m/Y', strtotime($coupon->end_date)) }}
                                @endif
                            @else
                                <span class="text-muted">No expiry</span>
                            @endif
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input change_status" 
                                       data-id="{{ $coupon->id }}"
                                       data-url="{{ url('admin/coupons/change_status') }}"
                                       @if($coupon->status) checked @endif>
                            </div>
                        </td>
                        <td>{{ get_date_in_timezone($coupon->created_at, 'd/m/Y') }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-horizontal-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @if(get_user_permission('coupons', 'u'))
                                    <a class="dropdown-item" 
                                       href="{{ route('admin.coupons.edit', ['id' => encrypt($coupon->id)]) }}">
                                        <i class="flaticon-pencil-1"></i> Edit
                                    </a>
                                    @endif

                                    @if(get_user_permission('coupons', 'r'))
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" 
                                       data-bs-target="#viewCouponModal{{$coupon->id}}">
                                        <i class="flaticon-eye"></i> View
                                    </a>
                                    @endif

                                    @if(get_user_permission('coupons', 'd'))
                                    <a class="dropdown-item" data-role="unlink"
                                       data-message="Do you want to delete this coupon?"
                                       href="{{ route('admin.coupons.delete', ['id' => encrypt($coupon->id)]) }}">
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

{{-- View Modals --}}
@foreach($list as $coupon)
<div class="modal fade" id="viewCouponModal{{$coupon->id}}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Coupon Details - {{ $coupon->code }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th>Code:</th>
                                <td><span class="badge bg-primary">{{ $coupon->code }}</span></td>
                            </tr>
                            <tr>
                                <th>Title (EN):</th>
                                <td>{{ $coupon->title_en }}</td>
                            </tr>
                            <tr>
                                <th>Title (AR):</th>
                                <td style="direction: rtl;">{{ $coupon->title_ar ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Title (BN):</th>
                                <td>{{ $coupon->title_bn ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Type:</th>
                                <td>
                                    @if($coupon->type == 'fixed')
                                        Fixed Amount
                                    @else
                                        Percentage
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Value:</th>
                                <td>
                                    @if($coupon->type == 'fixed')
                                        <img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($coupon->value, 2) }}
                                    @else
                                        {{ $coupon->value }}%
                                        @if($coupon->max_discount)
                                            (Max <img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($coupon->max_discount, 2) }})
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <th>Total Uses:</th>
                                <td>{{ $coupon->total_uses ?? 'Unlimited' }}</td>
                            </tr>
                            <tr>
                                <th>Used Count:</th>
                                <td>{{ $coupon->used_count }}</td>
                            </tr>
                            <tr>
                                <th>Per User Uses:</th>
                                <td>{{ $coupon->per_user_uses }}</td>
                            </tr>
                            <tr>
                                <th>Min Order Amount:</th>
                                <td><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($coupon->min_order_amount ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Valid From:</th>
                                <td>{{ $coupon->start_date ? date('d/m/Y', strtotime($coupon->start_date)) : 'Immediate' }}</td>
                            </tr>
                            <tr>
                                <th>Valid To:</th>
                                <td>{{ $coupon->end_date ? date('d/m/Y', strtotime($coupon->end_date)) : 'No expiry' }}</td>
                            </tr>
                        </table>
                    </div>
                    @if($coupon->description)
                    <div class="col-12 mt-3">
                        <h6>Description:</h6>
                        <p>{{ $coupon->description }}</p>
                    </div>
                    @endif

                    @if($coupon->apply_on != 'all')
                    <div class="col-12 mt-3">
                        <h6>Applicable On:</h6>
                        @if($coupon->apply_on == 'specific_products')
                            <p><strong>Products:</strong></p>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($coupon->products as $product)
                                <span class="badge bg-info">{{ $product->title_en }}</span>
                                @endforeach
                            </div>
                        @elseif($coupon->apply_on == 'specific_categories')
                            <p><strong>Categories:</strong></p>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($coupon->categories as $category)
                                <span class="badge bg-success">{{ $category->title }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endif

                    <div class="col-12 mt-3">
                        <h6>Restrictions:</h6>
                        <ul class="list-unstyled">
                            <li>
                                <i class="bx {{ $coupon->for_new_users_only ? 'bx-check-circle text-success' : 'bx-x-circle text-danger' }}"></i>
                                New Users Only
                            </li>
                            <li>
                                <i class="bx {{ $coupon->for_first_order_only ? 'bx-check-circle text-success' : 'bx-x-circle text-danger' }}"></i>
                                First Order Only
                            </li>
                        </ul>
                    </div>
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
$(document).ready(function() {
    $('.change_status').on('change', function() {
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
                status: status
            },
            success: function(res) {
                if (res.status == '1') {
                    App.alert(res.message, 'Success!', 'success');
                } else {
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    App.alert(res.message, 'Oops!', 'error');
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