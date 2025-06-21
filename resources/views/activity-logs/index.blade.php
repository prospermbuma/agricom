@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Activity Logs</h3>
                    <div class="card-tools">
                        <span class="badge badge-info bg-success">Total: {{ $logs->total() }} records</span>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="card-body">
                    <form method="GET" action="{{ route('activity-logs.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="user_id">Filter by User</label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value="">All Users</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="action">Filter by Action</label>
                                    <select name="action" id="action" class="form-control">
                                        <option value="">All Actions</option>
                                        @foreach($actions as $action)
                                            <option value="{{ $action }}" 
                                                {{ request('action') == $action ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $action)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date_from">Date From</label>
                                    <input type="date" 
                                           name="date_from" 
                                           id="date_from" 
                                           class="form-control" 
                                           value="{{ request('date_from') }}">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date_to">Date To</label>
                                    <input type="date" 
                                           name="date_to" 
                                           id="date_to" 
                                           class="form-control" 
                                           value="{{ request('date_to') }}">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary btn-sm mr-1">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Activity Logs Table -->
                    @if($logs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 15%">User</th>
                                        <th style="width: 15%">Action</th>
                                        <th style="width: 25%">Description</th>
                                        <th style="width: 15%">IP Address</th>
                                        <th style="width: 10%">Date</th>
                                        <th style="width: 10%">Time</th>
                                        <th style="width: 5%">Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $index => $log)
                                        <tr>
                                            <td>{{ $logs->firstItem() + $index }}</td>
                                            <td>
                                                @if($log->user)
                                                    <div>
                                                        <strong>{{ $log->user->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $log->user->email }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Unknown User</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $log->action == 'login' ? 'success' : ($log->action == 'logout' ? 'warning' : ($log->action == 'delete' ? 'danger' : 'primary')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $log->description ?? 'No description available' }}
                                            </td>
                                            <td>
                                                <code>{{ $log->ip_address ?? 'N/A' }}</code>
                                            </td>
                                            <td>
                                                {{ $log->created_at->format('M d, Y') }}
                                            </td>
                                            <td>
                                                {{ $log->created_at->format('H:i:s') }}
                                            </td>
                                            <td>
                                                @if($log->properties && count($log->properties) > 0)
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-info" 
                                                            data-toggle="modal" 
                                                            data-target="#logModal{{ $log->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <small class="text-muted">
                                    Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} results
                                </small>
                            </div>
                            <div>
                                {{ $logs->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No activity logs found</h5>
                            <p class="text-muted">Try adjusting your filters to see results.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals for Log Details -->
@foreach($logs as $log)
    @if($log->properties && count($log->properties) > 0)
        <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1" role="dialog" aria-labelledby="logModalLabel{{ $log->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logModalLabel{{ $log->id }}">
                            Activity Log Details - {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>User:</strong> {{ $log->user ? $log->user->name : 'Unknown' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Date:</strong> {{ $log->created_at->format('M d, Y H:i:s') }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Action:</strong> {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                            </div>
                            <div class="col-md-6">
                                <strong>IP Address:</strong> {{ $log->ip_address ?? 'N/A' }}
                            </div>
                        </div>
                        @if($log->description)
                            <div class="mb-3">
                                <strong>Description:</strong>
                                <p>{{ $log->description }}</p>
                            </div>
                        @endif
                        <div class="mb-3">
                            <strong>Additional Properties:</strong>
                            <pre class="bg-light p-3 rounded"><code>{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection

@push('styles')
<style>
    .table th {
        background-color: #343a40;
        color: white;
        border-color: #454d55;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0,123,255,.075);
    }
    
    .badge {
        font-size: 0.75em;
    }
    
    .card-tools .badge {
        font-size: 0.875rem;
    }
    
    .form-group label {
        font-weight: 600;
        color: #495057;
    }
    
    pre code {
        font-size: 0.875rem;
        line-height: 1.5;
    }
    
    .modal-lg {
        max-width: 800px;
    }
    
    .text-muted {
        font-size: 0.875rem;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-submit form when filters change (optional)
        $('#user_id, #action').change(function() {
            // Uncomment the line below if you want auto-submit on filter change
            // $(this).closest('form').submit();
        });
        
        // Clear date inputs
        $('.btn-secondary').click(function(e) {
            if ($(this).text().trim() === 'Clear') {
                $('#date_from, #date_to').val('');
            }
        });
    });
</script>
@endpush