@extends('layouts.dashboard')

@section('title', 'Download Management')

@section('header', 'Download Management')

@push('styles')
<style>
    .download-container {
        padding: 2rem;
        background-color: #f8fafc;
        min-height: calc(100vh - 100px);
    }

    .page-header {
        background-color: white;
        padding: 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .back-button {
        color: #6b7280;
        background: linear-gradient(to bottom, #ffffff, #f9fafb);
        border: 1px solid #e5e7eb;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        display: inline-flex;
        align-items: center;
        font-size: 0.875rem;
        transition: all 0.2s;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .back-button:hover {
        background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
        color: #374151;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transform: translateY(-1px);
    }

    .back-button i {
        margin-right: 0.5rem;
    }

    .download-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
        height: 100%;
    }
    
    .download-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .card-body {
        padding: 1.5rem;
    }
    
    .card-title {
        color: #1f2937;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .card-title i {
        margin-right: 0.75rem;
        font-size: 1.5rem;
    }
    
    .card-text {
        color: #6b7280;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }
    
    .btn {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn i {
        margin-right: 0.5rem;
        font-size: 1.1rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: white;
        border: 2px solid transparent;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #4338ca 0%, #2563eb 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
    }
    
    .btn-info {
        background: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 100%);
        color: white;
        border: 2px solid transparent;
    }
    
    .btn-info:hover {
        background: linear-gradient(135deg, #0891b2 0%, #0284c7 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(6, 182, 212, 0.3);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: 2px solid transparent;
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(16, 185, 129, 0.3);
    }

    .btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(rgba(255, 255, 255, 0.2), transparent);
        clip-path: polygon(0 0, 100% 0, 100% 25%, 0 55%);
        opacity: 0.3;
    }

    .btn:hover::after {
        opacity: 0.5;
    }

    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid #ffffff;
        border-top: 2px solid transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 0.5rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .success-message {
        position: fixed;
        bottom: 1rem;
        right: 1rem;
        background-color: #34d399;
        color: white;
        padding: 1rem 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 50;
        transform: translateY(100%);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .success-message.show {
        transform: translateY(0);
        opacity: 1;
    }
</style>
@endpush

@section('content')
<div class="download-container">
    <div class="page-header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
                <h1 class="text-2xl font-bold ml-4 text-gray-900">Download Center</h1>
            </div>
        </div>
    </div>

    <div class="container mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Reports Card -->
            <div class="download-card">
                <div class="card-body">
                    <h2 class="card-title">
                        <i class="fas fa-file-download text-blue-500"></i>
                        Reports
                    </h2>
                    <p class="card-text">
                        Download generated reports and analytics data for comprehensive business insights.
                    </p>
                    <button id="downloadReportBtn" class="btn btn-primary">
                        <i class="fas fa-file-download"></i>
                        Download Reports
                    </button>
                </div>
            </div>

            <!-- System Logs Card -->
            <div class="download-card">
                <div class="card-body">
                    <h2 class="card-title">
                        <i class="fas fa-history text-cyan-500"></i>
                        System Logs
                    </h2>
                    <p class="card-text">
                        Access and download detailed system logs and activity records for monitoring.
                    </p>
                    <button id="viewLogsBtn" class="btn btn-info">
                        <i class="fas fa-history"></i>
                        View Logs
                    </button>
                </div>
            </div>

            <!-- Data Export Card -->
            <div class="download-card">
                <div class="card-body">
                    <h2 class="card-title">
                        <i class="fas fa-file-export text-emerald-500"></i>
                        Data Export
                    </h2>
                    <p class="card-text">
                        Export system data in various formats for analysis and backup purposes.
                    </p>
                    <button id="exportDataBtn" class="btn btn-success">
                        <i class="fas fa-file-export"></i>
                        Export Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="successMessage" class="success-message" role="alert"></div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const downloadReportBtn = document.getElementById('downloadReportBtn');
    const viewLogsBtn = document.getElementById('viewLogsBtn');
    const exportDataBtn = document.getElementById('exportDataBtn');
    const successMessage = document.getElementById('successMessage');

    function showLoadingState(button) {
        const originalText = button.textContent;
        button.disabled = true;
        button.innerHTML = `
            <span class="loading-spinner"></span>
            Loading...
        `;
        return originalText;
    }

    function resetLoadingState(button, originalText) {
        button.disabled = false;
        button.innerHTML = originalText;
    }

    function showSuccessMessage(message) {
        successMessage.textContent = message;
        successMessage.classList.add('show');
        setTimeout(() => {
            successMessage.classList.remove('show');
        }, 3000);
    }

    if (downloadReportBtn) {
        downloadReportBtn.addEventListener('click', function() {
            const originalText = showLoadingState(this);
            fetch("{{ route('admin.reports.download') }}")
                .then(response => response.json())
                .then(data => {
                    showSuccessMessage(data.message || 'Report downloaded successfully');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to download report. Please try again.');
                })
                .finally(() => {
                    resetLoadingState(downloadReportBtn, originalText);
                });
        });
    }

    if (viewLogsBtn) {
        viewLogsBtn.addEventListener('click', function() {
            const originalText = showLoadingState(this);
            // Add logs viewing logic here
            setTimeout(() => {
                showSuccessMessage('System logs loaded successfully');
                resetLoadingState(viewLogsBtn, originalText);
            }, 1000);
        });
    }

    if (exportDataBtn) {
        exportDataBtn.addEventListener('click', function() {
            const originalText = showLoadingState(this);
            // Add data export logic here
            setTimeout(() => {
                showSuccessMessage('Data exported successfully');
                resetLoadingState(exportDataBtn, originalText);
            }, 1000);
        });
    }
});
</script>
@endpush 