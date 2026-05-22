@extends('admin.template.layout')

@section('content')
<div class="card mb-5">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">Notification History</h4>
        <a href="{{ route('admin.bulk_notifications.create') }}" class="btn btn-primary waves-effect waves-light">
            <i class="mdi mdi-plus me-1"></i> Send Bulk Notification
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Target Type</th>
                        <th>Target Users</th>
                        <th>Status</th>
                        <th>Created Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notification)
                        <tr>
                            <td>{{ $notification->id }}</td>
                            <td><strong>{{ $notification->title }}</strong></td>
                            <td title="{{ $notification->description }}">
                                {{ Str::limit($notification->description, 40) }}
                            </td>
                            <td>
                                @if(!empty($notification->user_types))
                                    @foreach($notification->user_types as $type)
                                        <span class="badge bg-info-subtle text-info">{{ $roles[$type] ?? 'Unknown' }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if(empty($notification->user_ids))
                                    <span class="badge bg-success-subtle text-success">All Users</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">{{ count($notification->user_ids) }} Specific Users</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-warning',
                                        'processing' => 'bg-info',
                                        'completed' => 'bg-success'
                                    ][$notification->status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($notification->status) }}</span>
                            </td>
                            <td>{{ $notification->created_at->format('d-M-Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No bulk notifications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($notifications->hasPages())
            <div class="mt-4">
                {!! $notifications->links('admin.template.pagination') !!}
            </div>
        @endif
    </div>
</div>
@stop
