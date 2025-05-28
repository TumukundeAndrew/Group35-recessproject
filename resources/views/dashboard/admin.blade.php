@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Vendor Validation</h3>
            @foreach ($vendors as $vendor)
                <p class="mb-2">{{ $vendor->name }} - Status: {{ $vendor->application_status }}</p>
            @endforeach
            <form action="http://localhost:50779/api/vendors" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="bg-amber-500 text-white p-2 rounded hover:bg-amber-600">Validate Vendors</button>
            </form>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Reports</h3>
            <p>Schedule and view system reports.</p>
            <form action="/reports" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="bg-amber-500 text-white p-2 rounded hover:bg-amber-600">View Reports</button>
            </form>
        </div>
    </div>
@endsection
