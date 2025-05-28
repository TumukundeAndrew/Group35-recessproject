@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Retail Shops</h3>
            <p>View available retail shops for sunflower oil.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Feedback</h3>
            <p>Submit feedback or complaints about products.</p>
            <form action="/feedback" method="POST" class="mt-4">
                @csrf
                <textarea name="feedback" class="w-full p-2 border rounded mb-2" placeholder="Enter your feedback"></textarea>
                <button type="submit" class="bg-amber-500 text-white p-2 rounded hover:bg-amber-600">Submit Feedback</button>
            </form>
        </div>
    </div>
@endsection
