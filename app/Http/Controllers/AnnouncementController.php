<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreAnnouncementRequest; // Requires you to create this file
use App\Http\Requests\UpdateAnnouncementRequest; // Requires you to create this file

class AnnouncementController extends Controller
{
    /**
     * Display bulletin board for citizens (Citizen Portal).
     */
    public function citizenIndex()
    {
        $user = Auth::user(); // User is guaranteed to exist by route middleware
        
        // Use Scopes in the Model (active, forAudience, byPriority) for cleaner query
        $announcements = Announcement::active()
                                   ->forAudience('citizens')
                                   ->byPriority()
                                   ->orderBy('is_pinned', 'desc')
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        // Separate pinned and regular announcements
        $pinnedAnnouncements = $announcements->where('is_pinned', true);
        $regularAnnouncements = $announcements->where('is_pinned', false);

        return view('citizen.bulletin-board', compact('user', 'pinnedAnnouncements', 'regularAnnouncements'));
    }

    /**
     * Display admin announcement management.
     */
    public function adminIndex()
    {
        $announcements = Announcement::with('creator')
                                   ->orderBy('is_pinned', 'desc')
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created announcement.
     */
    public function store(StoreAnnouncementRequest $request)
    {
        // Validation is handled by StoreAnnouncementRequest
        $validated = $request->validated();
        
        $validated['created_by'] = Auth::id() ?? 1; // Fallback ID

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('announcement_attachments', 'public');
        }

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement created successfully!');
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(int $id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement.
     */
    public function update(UpdateAnnouncementRequest $request, int $id)
    {
        $announcement = Announcement::findOrFail($id);
        // Validation is handled by UpdateAnnouncementRequest
        $validated = $request->validated();

        // Handle file upload (and deletion of old file if a new one is uploaded)
        if ($request->hasFile('attachment')) {
            // Delete old file if it exists
            if ($announcement->attachment_path) {
                Storage::disk('public')->delete($announcement->attachment_path);
            }
            $validated['attachment_path'] = $request->file('attachment')->store('announcement_attachments', 'public');
        }

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated successfully!');
    }

    /**
     * Remove the specified announcement.
     */
    public function destroy(int $id): JsonResponse
    {
        $announcement = Announcement::findOrFail($id);
        
        // Delete attachment file before deleting record
        if ($announcement->attachment_path) {
            Storage::disk('public')->delete($announcement->attachment_path);
        }
        
        $announcement->delete();

        return response()->json(['status' => 'success', 'message' => 'Announcement deleted successfully!']);
    }

    /**
     * Toggle announcement status (active/inactive).
     */
    public function toggleStatus(int $id): JsonResponse
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->update(['is_active' => !$announcement->is_active]);

        return response()->json(['status' => 'success', 'message' => 'Status updated!', 'is_active' => $announcement->is_active]);
    }

    /**
     * Toggle pin status.
     */
    public function togglePin(int $id): JsonResponse
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->update(['is_pinned' => !$announcement->is_pinned]);

        return response()->json(['status' => 'success', 'message' => 'Pin status updated!', 'is_pinned' => $announcement->is_pinned]);
    }

    /**
     * Download announcement attachment.
     */
    public function downloadAttachment(int $id)
    {
        $announcement = Announcement::findOrFail($id);
        
        if (!$announcement->attachment_path || !Storage::disk('public')->exists($announcement->attachment_path)) {
            abort(404, 'Attachment not found');
        }

        return Storage::disk('public')->download($announcement->attachment_path);
    }
}