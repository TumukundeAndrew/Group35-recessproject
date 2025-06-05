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
                        <h5 class="mb-0">Report Templates</h5>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
                        <i class="fas fa-plus"></i> Create Template
                    </button>
                </div>

                <div class="card-body">
                    <div class="row">
                        @foreach($templates as $type => $template)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $template['name'] }}</h5>
                                        <p class="card-text">{{ $template['description'] }}</p>
                                        <div class="mb-3">
                                            <strong>Available Formats:</strong>
                                            <div class="mt-2">
                                                @foreach($template['formats'] as $format)
                                                    <span class="badge bg-info me-2">{{ $format }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary edit-template" 
                                                    data-type="{{ $type }}"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editTemplateModal">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info preview-template"
                                                    data-type="{{ $type }}">
                                                <i class="fas fa-eye"></i> Preview
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Template Modal -->
<div class="modal fade" id="createTemplateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Report Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createTemplateForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Template Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
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
                        <label class="form-label">Available Formats</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="formats[]" value="PDF" checked>
                            <label class="form-check-label">PDF</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="formats[]" value="Excel">
                            <label class="form-check-label">Excel</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="formats[]" value="HTML">
                            <label class="form-check-label">HTML</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Template</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Template Modal -->
<div class="modal fade" id="editTemplateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Report Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editTemplateForm">
                <input type="hidden" name="template_type" id="editTemplateType">
                <div class="modal-body">
                    <!-- Same form fields as create modal -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle template creation
    const createTemplateForm = document.getElementById('createTemplateForm');
    if (createTemplateForm) {
        createTemplateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('/admin/reports/templates', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to create template. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while creating the template.');
            });
        });
    }

    // Handle template preview
    document.querySelectorAll('.preview-template').forEach(button => {
        button.addEventListener('click', function() {
            const templateType = this.dataset.type;
            window.open(`/admin/reports/templates/${templateType}/preview`, '_blank');
        });
    });
});
</script>
@endpush
@endsection 