@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Production Management</h3>
            <p>Manage production batches and schedules.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Workforce</h3>
            <p>Assign and schedule workforce tasks.</p>
            <form action="/workforce" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="bg-amber-500 text-white p-2 rounded hover:bg-amber-600">Manage Workforce</button>
            </form>
        </div>
    </div>
@endsection
