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
                        <h5 class="mb-0">Reports Management</h5>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Report Schedules</h5>
                                    <p class="card-text">Manage automated report schedules and their delivery to stakeholders.</p>
                                    <a href="{{ route('admin.reports.schedules') }}" class="btn btn-primary">
                                        <i class="fas fa-calendar"></i> Manage Schedules
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Report History</h5>
                                    <p class="card-text">View history of generated reports and their delivery status.</p>
                                    <a href="{{ route('admin.reports.history') }}" class="btn btn-info">
                                        <i class="fas fa-history"></i> View History
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Report Templates</h5>
                                    <p class="card-text">Customize report templates and formatting options.</p>
                                    <a href="{{ route('admin.reports.templates') }}" class="btn btn-success">
                                        <i class="fas fa-file-alt"></i> Manage Templates
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Recent Reports</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Generated</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentReports ?? [] as $report)
                                                <tr>
                                                    <td>{{ $report->name }}</td>
                                                    <td>{{ $report->created_at->diffForHumans() }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $report->status === 'sent' ? 'success' : ($report->status === 'failed' ? 'danger' : 'warning') }}">
                                                            {{ ucfirst($report->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Report Statistics</h5>
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <h3>{{ $stats->total ?? 0 }}</h3>
                                            <small class="text-muted">Total Reports</small>
                                        </div>
                                        <div class="col-4">
                                            <h3>{{ $stats->success_rate ?? '0%' }}</h3>
                                            <small class="text-muted">Success Rate</small>
                                        </div>
                                        <div class="col-4">
                                            <h3>{{ $stats->active_schedules ?? 0 }}</h3>
                                            <small class="text-muted">Active Schedules</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 