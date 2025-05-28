<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $workers = Worker::latest()->paginate(10);
        return view('workers.index', compact('workers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $supplycenters = ['Center A', 'Center B', 'Center C', 'Center D'];
        $roles = ['Manager', 'Supervisor', 'Operator', 'Assistant'];
        return view('workers.create', compact('supplycenters', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:workers',
            'supply_center' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'hours_assigned' => 'required|integer|min:0',
            'schedule_date' => 'required|date',
            'schedule_status' => 'required|in:pending,approved,rejected,completed',
            'phone' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/workers', $filename);
            $validated['image'] = $filename;
        }

        Worker::create($validated);

        return redirect()->route('workers.index')
            ->with('success', 'Worker created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Worker $worker)
    {
        $supplycenters = ['Center A', 'Center B', 'Center C', 'Center D'];
        $roles = ['Manager', 'Supervisor', 'Operator', 'Assistant'];
        return view('workers.edit', compact('worker', 'supplycenters', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Worker $worker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:workers,email,' . $worker->id,
            'supply_center' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'hours_assigned' => 'required|integer|min:0',
            'schedule_date' => 'required|date',
            'schedule_status' => 'required|in:pending,approved,rejected,completed',
            'phone' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($worker->image) {
                Storage::delete('public/workers/' . $worker->image);
            }
            
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/workers', $filename);
            $validated['image'] = $filename;
        }

        $worker->update($validated);

        return redirect()->route('workers.index')
            ->with('success', 'Worker updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Worker $worker)
    {
        if ($worker->image) {
            Storage::delete('public/workers/' . $worker->image);
        }
        
        $worker->delete();

        return redirect()->route('workers.index')
            ->with('success', 'Worker deleted successfully.');
    }
}
