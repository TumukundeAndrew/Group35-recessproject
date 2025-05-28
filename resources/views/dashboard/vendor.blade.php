@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Application Submission</h3>
            <p>Upload your PDF application to join the supply chain.</p>
            <form action="/vendor/application" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <input type="file" name="application_pdf" class="mb-2" accept="application/pdf">
                <button type="submit" class="bg-amber-500 text-white p-2 rounded hover:bg-amber-600">Submit Application</button>
            </form>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Application Status</h3>
            <p>Track the status of your application.</p>
        </div>
    </div>
@endsection
