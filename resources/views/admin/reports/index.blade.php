@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-3">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                        <h5 class="mb-0">Report Schedules</h5>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createReportModal">
                        Create New Schedule
                    </button>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Frequency</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Stakeholders</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportSchedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $schedule->type === 'inventory' ? 'info' : ($schedule->type === 'logistics' ? 'warning' : 'success') }}">
                                                {{ ucfirst($schedule->type) }}
                                            </span>
                                        </td>
                                        <td>{{ ucfirst($schedule->frequency) }}</td>
                                        <td>{{ $schedule->scheduled_time->format('H:i') }}</td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input status-toggle" type="checkbox" 
                                                    data-id="{{ $schedule->id }}" 
                                                    {{ $schedule->is_active ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            @foreach($schedule->stakeholders as $stakeholder)
                                                <span class="badge bg-secondary">{{ $stakeholder->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-info view-customizations" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#customizationsModal"
                                                    data-schedule="{{ json_encode($schedule->load('stakeholders')) }}">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-warning edit-schedule"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editReportModal"
                                                    data-schedule="{{ json_encode($schedule) }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-schedule"
                                                    data-id="{{ $schedule->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Report Modal -->
<div class="modal fade" id="createReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Report Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createReportForm" action="{{ route('admin.reports.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select class="form-control" name="type" required>
                            <option value="inventory">Inventory</option>
                            <option value="logistics">Logistics</option>
                            <option value="financial">Financial</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Frequency</label>
                        <select class="form-control" name="frequency" required>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Scheduled Time</label>
                        <input type="time" class="form-control" name="scheduled_time" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stakeholders</label>
                        <select class="form-control" name="stakeholders[]" multiple required>
                            @foreach($stakeholders as $stakeholder)
                                <option value="{{ $stakeholder->id }}">{{ $stakeholder->name }} ({{ ucfirst($stakeholder->type) }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Customizations Modal -->
<div class="modal fade" id="customizationsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report Customizations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="customizationsContent"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle status toggle
    $('.status-toggle').change(function() {
        const id = $(this).data('id');
        const isActive = $(this).prop('checked');
        
        $.ajax({
            url: `/admin/reports/${id}/toggle-status`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                is_active: isActive
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Status updated successfully');
                }
            },
            error: function() {
                toastr.error('Error updating status');
                $(this).prop('checked', !isActive);
            }
        });
    });

    // Handle view customizations
    $('.view-customizations').click(function() {
        const schedule = JSON.parse($(this).data('schedule'));
        let content = '<div class="list-group">';
        
        schedule.stakeholders.forEach(stakeholder => {
            const customizations = JSON.parse(stakeholder.pivot.customizations || '{}');
            content += `
                <div class="list-group-item">
                    <h6>${stakeholder.name}</h6>
                    <ul class="list-unstyled mb-0">
                    ${Object.entries(customizations).map(([key, value]) => `
                        <li>
                            <small>
                                ${key.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')}:
                                ${value ? '✓' : '✗'}
                            </small>
                        </li>
                    `).join('')}
                    </ul>
                </div>
            `;
        });
        
        content += '</div>';
        $('#customizationsContent').html(content);
    });

    // Handle delete
    $('.delete-schedule').click(function() {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this schedule?')) {
            $.ajax({
                url: `/admin/reports/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Schedule deleted successfully');
                        location.reload();
                    }
                },
                error: function() {
                    toastr.error('Error deleting schedule');
                }
            });
        }
    });
});
</script>
@endpush
@endsection 