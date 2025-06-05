<form id="sendReportForm" class="space-y-6" method="POST" action="{{ route('admin.reports.send') }}">
    @csrf
    <!-- Stakeholder Type Selection -->
    <div>
        <label for="stakeholder_type" class="block text-sm font-medium text-gray-700">Stakeholder Type</label>
        <select name="stakeholder_type" id="stakeholder_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">Select Type</option>
            <option value="admin">Admin</option>
            <option value="supplier">Supplier</option>
            <option value="vendor">Vendor</option>
            <option value="wholesaler">Wholesaler</option>
            <option value="manufacturer">Manufacturer</option>
        </select>
    </div>

    <!-- Stakeholders Selection (will be populated via JavaScript) -->
    <div id="stakeholdersContainer" style="display: none;">
        <label class="block text-sm font-medium text-gray-700">Select Stakeholders</label>
        <div id="stakeholdersList" class="mt-2 space-y-2 max-h-60 overflow-y-auto"></div>
    </div>

    <!-- Message -->
    <div>
        <label for="message" class="block text-sm font-medium text-gray-700">Custom Message (Optional)</label>
        <textarea name="message" id="message" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Add a custom message to include with the report..."></textarea>
    </div>

    <!-- Report Options -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Report Options</label>
        <div class="space-y-2">
            <label class="inline-flex items-center">
                <input type="checkbox" name="include_analytics" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <span class="ml-2">Include Analytics</span>
            </label>
            <label class="inline-flex items-center">
                <input type="checkbox" name="include_summary" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500" checked>
                <span class="ml-2">Include Summary</span>
            </label>
        </div>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Send Report
        </button>
    </div>
</form>

<script>
document.getElementById('stakeholder_type').addEventListener('change', function() {
    const type = this.value;
    const container = document.getElementById('stakeholdersContainer');
    const list = document.getElementById('stakeholdersList');

    if (!type) {
        container.style.display = 'none';
        return;
    }

    // Show loading state
    container.style.display = 'block';
    list.innerHTML = '<div class="text-center"><span class="loading-spinner"></span> Loading stakeholders...</div>';

    // Fetch stakeholders of selected type
    fetch('{{ route("admin.api.stakeholders.by.type", ["type" => ""]) }}' + type, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(stakeholders => {
        list.innerHTML = stakeholders.length ? '' : '<p class="text-gray-500">No stakeholders found for this type.</p>';
        
        stakeholders.forEach(stakeholder => {
            const div = document.createElement('div');
            div.className = 'flex items-center';
            div.innerHTML = `
                <input type="checkbox" 
                       name="stakeholders[]" 
                       value="${stakeholder.id}" 
                       id="stakeholder_${stakeholder.id}"
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <label for="stakeholder_${stakeholder.id}" class="ml-2">
                    ${stakeholder.name} (${stakeholder.email})
                </label>
            `;
            list.appendChild(div);
        });
    })
    .catch(error => {
        console.error('Error:', error);
        list.innerHTML = '<p class="text-red-500">Error loading stakeholders. Please try again.</p>';
    });
});

// Prevent form from submitting normally
document.getElementById('sendReportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="loading-spinner"></span>Sending...';

    const formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'mt-4 p-4 rounded ' + 
            (data.success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
        messageDiv.textContent = data.message;
        
        const existingMessage = document.querySelector('.message');
        if (existingMessage) {
            existingMessage.replaceWith(messageDiv);
        } else {
            this.appendChild(messageDiv);
        }
        
        if (data.success) {
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while sending the report. Please try again.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Send Report';
    });
});
</script> 