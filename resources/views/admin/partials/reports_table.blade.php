<table class="min-w-full bg-white mb-4 rounded shadow">
    <thead>
        <tr>
            <th class="px-4 py-2">Report Name</th>
            <th class="px-4 py-2">Date Generated</th>
            <th class="px-4 py-2">Download</th>
        </tr>
    </thead>
    <tbody>
        @forelse($reports as $report)
            <tr>
                <td>{{ $report->file_path ? basename($report->file_path, '.pdf') : 'N/A' }}</td>
                <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    @if($report->file_path)
                        <a href="{{ asset($report->file_path) }}" target="_blank" class="text-blue-600 underline">Download</a>
                    @else
                        N/A
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center py-2">No reports generated yet.</td>
            </tr>
        @endforelse
    </tbody>
</table> 