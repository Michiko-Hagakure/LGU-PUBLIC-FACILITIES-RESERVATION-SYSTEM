<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceLog;
use App\Models\Facility;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMaintenanceLogRequest; // Requires you to create this file
use App\Http\Requests\UpdateMaintenanceLogRequest; // Requires you to create this file
use App\Http\Requests\UpdateMaintenanceStatusRequest; // Requires you to create this file

class MaintenanceLogController extends Controller
{
    /**
     * Display a listing of maintenance logs.
     */
    public function index(Request $request)
    {
        $query = MaintenanceLog::with('facility');

        // Cleaned up filtering logic
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('facility_id') && $request->facility_id !== 'all') {
            $query->where('facility_id', $request->facility_id);
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('maintenance_type', $request->type);
        }

        // Search logic
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('assigned_to', 'LIKE', "%{$search}%");
            });
        }

        $logs = $query->orderBy('due_date', 'asc')->paginate(20);
        $facilities = Facility::select('facility_id', 'name')->get();

        return view('admin.maintenance-logs.index', compact('logs', 'facilities'));
    }

    /**
     * Show the form for creating a new maintenance log.
     */
    public function create()
    {
        $facilities = Facility::select('facility_id', 'name')->get();
        return view('admin.maintenance-logs.create', compact('facilities'));
    }

    /**
     * Store a newly created maintenance log in storage.
     */
    public function store(StoreMaintenanceLogRequest $request)
    {
        $validated = $request->validated();
        $validated['reported_by'] = auth()->id() ?? 1; // Fallback ID
        $validated['status'] = 'pending'; // New logs start as pending

        MaintenanceLog::create($validated);

        return redirect()->route('admin.maintenance-logs.index')
            ->with('success', 'Maintenance log created successfully!');
    }

    /**
     * Display the specified maintenance log.
     */
    public function show($id)
    {
        $log = MaintenanceLog::with('facility')->findOrFail($id);
        return view('admin.maintenance-logs.show', compact('log'));
    }

    /**
     * Show the form for editing the specified maintenance log.
     */
    public function edit($id)
    {
        $log = MaintenanceLog::findOrFail($id);
        $facilities = Facility::select('facility_id', 'name')->get();
        return view('admin.maintenance-logs.edit', compact('log', 'facilities'));
    }

    /**
     * Update the specified maintenance log in storage.
     */
    public function update(UpdateMaintenanceLogRequest $request, $id)
    {
        $maintenanceLog = MaintenanceLog::findOrFail($id);
        $validated = $request->validated();

        // Auto-set completed_date if status changed to completed
        if (isset($validated['status']) && $validated['status'] === 'completed' && !$maintenanceLog->completed_date) {
            $validated['completed_date'] = now()->format('Y-m-d');
        }

        $maintenanceLog->update($validated);

        return redirect()->route('admin.maintenance-logs.index')
            ->with('success', 'Maintenance log updated successfully!');
    }

    /**
     * Remove the specified maintenance log.
     */
    public function destroy($id)
    {
        $maintenanceLog = MaintenanceLog::findOrFail($id);
        $maintenanceLog->delete();

        return redirect()->route('admin.maintenance-logs.index')
            ->with('success', 'Maintenance log deleted successfully!');
    }

    /**
     * Update the status of a maintenance log.
     * Uses a dedicated request for simple status update.
     */
    public function updateStatus(UpdateMaintenanceStatusRequest $request, $id)
    {
        $maintenanceLog = MaintenanceLog::findOrFail($id);
        $validated = $request->validated();

        if ($validated['status'] === 'completed') {
            $validated['completed_date'] = now()->format('Y-m-d');
        } else {
            // Ensure completed_date is cleared if status reverts from completed
            $validated['completed_date'] = null;
        }

        $maintenanceLog->update($validated);

        return redirect()->back()->with('success', 'Status updated successfully!');
    }
}