{{-- resources/views/admin/coupons/report.blade.php --}}
@extends('admin.template.layout')

@section('content')
<div class="card mb-5">
    <div class="card-header">
        <h5 class="card-title">Filter Report</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.coupons.report') }}" class="row">
            <div class="col-md-3 mb-3">
                <label>Coupon</label>
                <select name="coupon_id" class="form-control select2">
                    <option value="">All Coupons</option>
                    @foreach($coupons as $coupon)
                    <option value="{{ $coupon->id }}" {{ $request->coupon_id == $coupon->id ? 'selected' : '' }}>
                        {{ $coupon->code }} - {{ $coupon->title_en }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label>Date From</label>
                <input type="date" name="date_from" class="form-control" value="{{ $request->date_from }}">
            </div>

            <div class="col-md-3 mb-3">
                <label>Date To</label>
                <input type="date" name="date_to" class="form-control" value="{{ $request->date_to }}">
            </div>

            <div class="col-md-3 mb-3">
                <label>User</label>
                <input type="text" name="user" class="form-control" value="{{ $request->user }}" 
                       placeholder="Search by name or email">
            </div>

            <div class="col-12 d-flex gap-2 justify-content-start mt-3">
                <button type="submit" class="btn btn-primary waves-effect waves-light">
                    <i class="bi bi-file-earmark-text"></i> Search
                </button>
                <a href="{{ route('admin.coupons.report') }}" class="btn btn-outline-secondary waves-effect waves-light">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Discount Given</h6>
                <h3><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($summary['total_discount'], 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Total Coupon Uses</h6>
                <h3>{{ $summary['total_uses'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 d-none">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Unique Coupons</h6>
                <h3>{{ $summary['unique_coupons'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 d-none">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Unique Users</h6>
                <h3>{{ $summary['unique_users'] }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Coupon Usage Details</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="example2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Coupon Code</th>
                        <th>Coupon Name</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Order</th>
                        <th>Discount Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usages as $index => $usage)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ date('d/m/Y H:i', strtotime($usage->used_at)) }}</td>
                        <td><span class="badge bg-primary">{{ $usage->code }}</span></td>
                        <td>{{ $usage->coupon_name }}</td>
                        <td>{{ $usage->user_name }}</td>
                        <td>{{ $usage->user_email }}</td>
                        <td>
                            @if($usage->order_number)
                                <a href="{{ route('admin.orders.view', ['id' => encrypt($usage->order_id)]) }} ">
                                    {{ $usage->order_number }}
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td><img class="aed-symbol" src="{{ asset('assets/img/Dirham_Symbol.svg') }}">{{ number_format($usage->discount_amount, 2) }}</td>
                    </tr>
                    
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection