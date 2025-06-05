@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary me-3">
                            <i class="fas fa-arrow-left"></i> Back to Reports
                        </a>
                        <h5 class="mb-0">Report History</h5>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Report Name</th>
                                    <th>Schedule</th>
                                    <th>Stakeholder</th>
                                    <th>Generated At</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                    <tr>
                                        <td>{{ $report->schedule->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $report->schedule->type === 'inventory' ? 'info' : ($report->schedule->type === 'logistics' ? 'warning' : 'success') }}">
                                                {{ ucfirst($report->schedule->type) }}
                                            </span>
                                        </td>
                                        <td>{{ $report->stakeholder->name }}</td>
                                        <td>{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $report->status === 'sent' ? 'success' : ($report->status === 'failed' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($report->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.reports.download', ['stakeholder_id' => $report->stakeholder_id]) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                @if($report->status === 'failed')
                                                    <button type="button" 
                                                            class="btn btn-sm btn-warning retry-report" 
                                                            data-id="{{ $report->id }}">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No report history found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle retry button clicks
    document.querySelectorAll('.retry-report').forEach(button => {
        button.addEventListener('click', function() {
            const reportId = this.dataset.id;
            if (confirm('Are you sure you want to retry sending this report?')) {
                // Send retry request
                fetch(`/admin/reports/${reportId}/retry`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to retry report. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while retrying the report.');
                });
            }
        });
    });
});
</script>
@endpush
@endsection 