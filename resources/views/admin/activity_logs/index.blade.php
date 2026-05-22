

@extends("admin.template.layout")

@section("content")

<div class="card mb-5">
   
   
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
    <h4>{{ $page_heading }}</h4>

    <a href="{{ route('admin.activity.logs.export', request()->query()) }}"
       class="btn btn-success">
        Export Excel
    </a>
</div>
        <div class="table-responsive">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Sr#</th>
                        <th>User</th>
                        <th>Role</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Details</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($logs as $key => $log)
                        <tr>
                            <td>{{ ($logs->currentPage() - 1) * $logs->perPage() + $key + 1 }}</td>

                            <!-- User -->
                            <td>
                                {{ $log->user->name ?? 'N/A' }}
                            </td>

                            <!-- Role -->
                            <td>
                                {{ $log->user->role_name ?? 'N/A' }}
                            </td>

                            <!-- Action -->
                            <td>
                                <span class="badge bg-primary">
                                    {{ str_replace('_', ' ', $log->action) }}
                                </span>
                            </td>

                            <!-- Description -->
                            <td>
                                {{ $log->description }}
                            </td>

                            <!-- Meta -->
                            <td>
                                @if($log->meta)
                                    @foreach($log->meta as $key => $value)
                                        <strong>{{ $key }}:</strong> {{ $value }} <br>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>

                            <!-- Date -->
                            <td>
                                {{ $log->created_at->timezone('Asia/Dubai')->format('d-M-Y h:i A') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $logs->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
</div>

@endsection