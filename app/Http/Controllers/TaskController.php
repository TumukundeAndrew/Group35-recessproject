<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('role', request('role'))
                     ->latest()
                     ->get();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expected_date' => 'required|date',
            'status' => 'required|in:pending,completed',
            'role' => 'required|string'
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'expected_date' => $request->expected_date,
            'status' => $request->status,
            'role' => $request->role,
            'user_id' => Auth::id()
        ]);

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expected_date' => 'required|date',
            'status' => 'required|in:pending,completed',
            'role' => 'required|string'
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'expected_date' => $request->expected_date,
            'status' => $request->status,
            'role' => $request->role
        ]);

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(null, 204);
    }
} 