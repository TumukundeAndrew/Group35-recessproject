@extends('layouts.dashboard')

@section('title', 'Supplier Reports')

@section('header', 'Supplier Reports')

@push('styles')
<style>
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background: rgba(0,0,0,0.4); }
    .modal-content { background: #fff; margin: 10% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 500px; }
</style>
@endpush

@section('content')
<input type="hidden" id="currentUserRole" value="{{ auth()->user()->role }}">
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Main Report Content -->
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Reports Overview</h2>
            <p class="text-gray-700 text-base">
                Welcome to the supplier reports section. Here you can view summaries of activities, performance data, and workforce allocations related to your supply operations.
            </p>
        </div>

        <!-- Tasks Section -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Supplier Tasks</h3>
                <button id="addTaskBtn" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">Add Task</button>
            </div>
            <button id="viewTasksBtn" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 mb-4">View Tasks</button>
            <div id="tasksSection" class="overflow-x-auto" style="display:none;">
                <table class="min-w-full bg-white mb-4 rounded shadow">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Title</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Expected Date</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tasksTableBody"></tbody>
                </table>
            </div>
        </div>

        <!-- Sent Reports Card -->
        <div class="bg-white shadow rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold mb-4">Sent Reports</h3>
            <div id="sentReportsSection" class="mt-4">
                <table class="min-w-full bg-white mb-4 rounded shadow">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Report Name</th>
                            <th class="px-4 py-2">Sent To</th>
                            <th class="px-4 py-2">Date Sent</th>
                            <th class="px-4 py-2">Download</th>
                        </tr>
                    </thead>
                    <tbody id="sentReportsTableBody">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="taskModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display:none;">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
        <h3 id="modalTitle" class="text-lg font-semibold mb-2">Add/Edit Task</h3>
        <form id="taskForm">
            <input type="hidden" id="taskId" name="taskId">
            <div class="mb-2">
                <label>Title</label>
                <input type="text" id="taskTitle" name="title" class="w-full border rounded px-2 py-1" required>
            </div>
            <div class="mb-2">
                <label>Description</label>
                <textarea id="taskDescription" name="description" class="w-full border rounded px-2 py-1"></textarea>
            </div>
            <div class="mb-2">
                <label>Expected Date</label>
                <input type="date" id="taskExpectedDate" name="expected_date" class="w-full border rounded px-2 py-1" required>
            </div>
            <div class="mb-2">
                <label>Status</label>
                <select id="taskStatus" name="status" class="w-full border rounded px-2 py-1">
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="flex justify-end mt-4">
                <button type="button" id="closeModalBtn" class="mr-2 px-3 py-1 rounded bg-gray-400 text-white">Cancel</button>
                <button type="submit" class="px-3 py-1 rounded bg-blue-600 text-white">Save</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// use Barryvdh\DomPDF\Facades\Pdf;

const role = 'supplier';
const tasksSection = document.getElementById('tasksSection');
const viewTasksBtn = document.getElementById('viewTasksBtn');
const addTaskBtn = document.getElementById('addTaskBtn');
const tasksTableBody = document.getElementById('tasksTableBody');
const taskModal = document.getElementById('taskModal');
const closeModalBtn = document.getElementById('closeModalBtn');
const taskForm = document.getElementById('taskForm');
const modalTitle = document.getElementById('modalTitle');

let editingTaskId = null;

viewTasksBtn.onclick = function() {
    tasksSection.style.display = tasksSection.style.display === 'none' ? 'block' : 'none';
    if (tasksSection.style.display === 'block') fetchTasks();
};
addTaskBtn.onclick = function() {
    openTaskModal();
};
closeModalBtn.onclick = function() {
    closeTaskModal();
};

function fetchTasks() {
    fetch(`/tasks?role=${role}`)
        .then(res => res.json())
        .then(tasks => {
            tasksTableBody.innerHTML = '';
            tasks.forEach(task => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-4 py-2">${task.title}</td>
                    <td class="px-4 py-2">${task.description || ''}</td>
                    <td class="px-4 py-2">${task.expected_date}</td>
                    <td class="px-4 py-2">${task.status}</td>
                    <td class="px-4 py-2">
                        <button class='editTaskBtn bg-yellow-500 text-white px-2 py-1 rounded' data-id='${task.id}'>Edit</button>
                    </td>
                `;
                tasksTableBody.appendChild(tr);
            });
            document.querySelectorAll('.editTaskBtn').forEach(btn => {
                btn.onclick = function() {
                    const id = this.getAttribute('data-id');
                    const task = tasks.find(t => t.id == id);
                    openTaskModal(task);
                };
            });
        });
}

function openTaskModal(task = null) {
    editingTaskId = task ? task.id : null;
    modalTitle.textContent = task ? 'Edit Task' : 'Add Task';
    taskForm.reset();
    if (task) {
        document.getElementById('taskId').value = task.id;
        document.getElementById('taskTitle').value = task.title;
        document.getElementById('taskDescription').value = task.description;
        document.getElementById('taskExpectedDate').value = task.expected_date;
        document.getElementById('taskStatus').value = task.status;
    }
    taskModal.style.display = 'block';
}

function closeTaskModal() {
    taskModal.style.display = 'none';
}

taskForm.onsubmit = function(e) {
    e.preventDefault();
    const data = {
        role,
        title: document.getElementById('taskTitle').value,
        description: document.getElementById('taskDescription').value,
        expected_date: document.getElementById('taskExpectedDate').value,
        status: document.getElementById('taskStatus').value,
    };
    let url = '/tasks', method = 'POST';
    if (editingTaskId) {
        url = `/tasks/${editingTaskId}`;
        method = 'PUT';
    }
    fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(() => {
        closeTaskModal();
        fetchTasks();
    });
};

window.onclick = function(event) {
    if (event.target == taskModal) closeTaskModal();
};

document.addEventListener('DOMContentLoaded', function() {
    const sentReportsTableBody = document.getElementById('sentReportsTableBody');
    const currentUserRole = document.getElementById('currentUserRole')?.value;
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.placeholder = 'Search reports...';
    searchInput.className = 'mb-2 px-2 py-1 border rounded w-full';
    sentReportsTableBody.parentElement.parentElement.insertBefore(searchInput, sentReportsTableBody.parentElement);

    let reportsData = [];
    let sortKey = null;
    let sortAsc = true;

    function renderTable(data) {
        sentReportsTableBody.innerHTML = '';
        if (!data.length) {
            sentReportsTableBody.innerHTML = '<tr><td colspan="7" class="text-center py-2">No reports sent yet.</td></tr>';
        } else {
            data.forEach(report => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${report.name}</td>
                    <td>${report.sent_to}</td>
                    <td>${report.date_sent}</td>
                    <td>${report.type || 'Sunflower Oil'}</td>
                    <td>${report.status || 'Sent'}</td>
                    <td>${report.file_size ? (report.file_size + ' KB') : ''}</td>
                    <td>${report.file ? `<a href='/admin/download-sunflower-report' target='_blank' class='text-blue-600 underline'>Download</a>` : ''}</td>
                `;
                sentReportsTableBody.appendChild(tr);
            });
        }
    }

    function filterAndRender() {
        let filtered = reportsData.filter(report => {
            // Filter by role/group
            if (!currentUserRole) return true;
            if (report.sent_to && report.sent_to !== 'N/A') {
                // Optionally, you could map emails to roles if needed
                return report.sent_to.toLowerCase().includes(currentUserRole);
            }
            return true;
        });
        // Search
        const q = searchInput.value.toLowerCase();
        if (q) {
            filtered = filtered.filter(report =>
                (report.name && report.name.toLowerCase().includes(q)) ||
                (report.sent_to && report.sent_to.toLowerCase().includes(q)) ||
                (report.date_sent && report.date_sent.toLowerCase().includes(q)) ||
                (report.type && report.type.toLowerCase().includes(q))
            );
        }
        // Sort
        if (sortKey) {
            filtered.sort((a, b) => {
                if (a[sortKey] < b[sortKey]) return sortAsc ? -1 : 1;
                if (a[sortKey] > b[sortKey]) return sortAsc ? 1 : -1;
                return 0;
            });
        }
        renderTable(filtered);
    }

    // Add sorting to headers
    const thead = sentReportsTableBody.parentElement.parentElement.querySelector('thead tr');
    if (thead) {
        const keys = ['name', 'sent_to', 'date_sent', 'type', 'status', 'file_size'];
        thead.querySelectorAll('th').forEach((th, idx) => {
            if (idx < keys.length) {
                th.style.cursor = 'pointer';
                th.onclick = function() {
                    if (sortKey === keys[idx]) {
                        sortAsc = !sortAsc;
                    } else {
                        sortKey = keys[idx];
                        sortAsc = true;
                    }
                    filterAndRender();
                };
            }
        });
    }

    searchInput.addEventListener('input', filterAndRender);

    if (sentReportsTableBody) {
        fetch('/admin/reports/sent', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(reports => {
            // Add extra columns if not present
            reportsData = reports.map(report => ({
                ...report,
                type: report.type || 'Sunflower Oil',
                status: report.status || 'Sent',
                file_size: report.file ? (Math.round((report.file_size || 0) / 1024)) : '',
            }));
            filterAndRender();
        })
        .catch(() => {
            sentReportsTableBody.innerHTML = '<tr><td colspan="7" class="text-center py-2 text-red-600">Failed to load sent reports.</td></tr>';
        });
    }
});
</script>
@endpush
@endsection 