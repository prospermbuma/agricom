@extends('layouts.app')

@section('title', 'Activity Logs - Agricom')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 rounded-4 p-4 shadow-sm">
                    <!-- Card Header -->
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="fw-bold mb-0">
                                    <i class="fas fa-history text-primary me-2"></i>Activity Logs
                                </h3>
                                <small class="text-muted">Track system activities and user actions</small>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success">
                                Total: {{ $logs->total() }} records
                            </span>
                        </div>
                    </div>

                    <!-- Filters Section -->
                    <div class="card-body">
                        <form method="GET" action="{{ route('activity-logs.index') }}" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="user_id" class="form-label fw-semibold">Filter by User</label>
                                    <select name="user_id" id="user_id" class="form-select">
                                        <option value="">All Users</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="action" class="form-label fw-semibold">Filter by Action</label>
                                    <select name="action" id="action" class="form-select">
                                        <option value="">All Actions</option>
                                        @foreach ($actions as $action)
                                            <option value="{{ $action }}"
                                                {{ request('action') == $action ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $action)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label for="date_from" class="form-label fw-semibold">Date From</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control"
                                        value="{{ request('date_from') }}">
                                </div>

                                <div class="col-md-2">
                                    <label for="date_to" class="form-label fw-semibold">Date To</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control"
                                        value="{{ request('date_to') }}">
                                </div>

                                <div class="col-md-2 d-flex align-items-end">
                                    <div class="d-flex w-100 gap-2">
                                        <button type="submit" class="btn btn-primary flex-grow-1">
                                            <i class="fas fa-filter me-1"></i> Filter
                                        </button>
                                        <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Activity Logs Table -->
                        @if ($logs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="15%">User</th>
                                            <th width="20%">Action</th>
                                            <th width="25%">Description</th>
                                            <th width="15%">IP Address</th>
                                            <th width="10%">Date & Time</th>
                                            <th width="10%">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($logs as $index => $log)
                                            <tr>
                                                <td class="text-muted">{{ $logs->firstItem() + $index }}</td>
                                                <td>
                                                    @if ($log->user)
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar avatar-sm me-2">
                                                                <img src="{{ $log->user->avatar_url }}"
                                                                    class="rounded-circle" alt="{{ $log->user->name }}">
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold">{{ $log->user->name }}</div>
                                                                <small class="text-muted">{{ $log->user->email }}</small>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">System</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                                        {{ ucfirst(str_replace('_', ' ', $log->action ?? 'Unknown')) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 300px;">
                                                        {{ $log->description ?? 'No description available' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="font-monospace">{{ $log->properties['ip_address'] ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    <div>{{ $log->created_at->format('M d, Y') }}</div>
                                                    <small
                                                        class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                                </td>
                                                <td>
                                                    @if ($log->properties && count($log->properties) > 0)
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#logModal{{ $log->id }}">
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
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted">
                                    Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }}
                                    entries
                                </div>
                                <div>
                                    {{ $logs->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5 my-4">
                                <div class="bg-light rounded-circle p-4 d-inline-block mb-3">
                                    <i class="fas fa-search fa-2x text-muted"></i>
                                </div>
                                <h5 class="fw-semibold">No activity logs found</h5>
                                <p class="text-muted">Try adjusting your search filters</p>
                                <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-sync me-1"></i> Reset Filters
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals for Log Details -->
    @foreach ($logs as $log)
        @if ($log->properties && count($log->properties) > 0)
            <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1"
                aria-labelledby="logModalLabel{{ $log->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-semibold" id="logModalLabel{{ $log->id }}">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Activity Details: {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6 class="fw-semibold mb-2">User Information</h6>
                                        @if ($log->user)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-md me-3">
                                                    <img src="{{ $log->user->avatar_url }}"
                                                        class="rounded-circle" alt="{{ $log->user->name }}">
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $log->user->name }}</div>
                                                    <small class="text-muted">{{ $log->user->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">System Activity</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6 class="fw-semibold mb-2">Technical Details</h6>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">IP Address:</small>
                                            <span class="font-monospace">{{ $log->properties['ip_address'] ?? 'N/A' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Timestamp:</small>
                                            <span>{{ $log->created_at->format('M d, Y H:i:s') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="fw-semibold mb-2">Action Description</h6>
                                <div class="bg-light p-3 rounded">
                                    {{ $log->description ?? 'No description provided' }}
                                </div>
                            </div>

                            <div>
                                <h6 class="fw-semibold mb-2">Additional Properties</h6>
                                <div class="bg-dark text-light p-3 rounded">
                                    <pre class="mb-0"><code>{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="window.print()">
                                <i class="fas fa-print me-1"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection

@section('styles')
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #8BC34A;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --light-text: #7f8c8d;
        }

        body {
            background-color: #f5f7fb;
        }

        .table th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(76, 175, 80, 0.05);
        }

        .badge {
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 6px;
        }

        .font-monospace {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 0.875rem;
        }

        pre code {
            font-size: 0.875rem;
            line-height: 1.5;
            color: #e83e8c;
        }

        .modal-lg {
            max-width: 900px;
        }

        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .btn-outline-primary {
            border-radius: 6px;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit form when filters change (optional)
            const filters = ['user_id', 'action'];
            filters.forEach(filter => {
                document.getElementById(filter)?.addEventListener('change', function() {
                    // Uncomment to enable auto-submit
                    // this.closest('form').submit();
                });
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
