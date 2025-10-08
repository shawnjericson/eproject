<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactReplyRequest;
use App\Http\Requests\Admin\ContactStatusUpdateRequest;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display a listing of contact messages
     */
    public function index(Request $request)
    {
        $query = Contact::with('repliedBy');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search by name, email, or subject
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        // Order by newest first
        $contacts = $query->orderBy('created_at', 'desc')->paginate(20);

        // Count by status for filter buttons
        $newCount = Contact::where('status', 'new')->count();
        $readCount = Contact::where('status', 'read')->count();
        $repliedCount = Contact::where('status', 'replied')->count();
        $archivedCount = Contact::where('status', 'archived')->count();

        return view('admin.contacts.index', compact(
            'contacts',
            'newCount',
            'readCount',
            'repliedCount',
            'archivedCount'
        ));
    }

    /**
     * Display the specified contact message
     */
    public function show(Contact $contact)
    {
        // Load relationship
        $contact->load('repliedBy');

        // Mark as read if it's new
        if ($contact->status === 'new') {
            $contact->markAsRead();
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Reply to a contact message
     */
    public function reply(ContactReplyRequest $request, Contact $contact)
    {
        try {
            // Save reply
            $contact->markAsReplied($request->reply, Auth::id());

            Log::info('Admin replied to contact message:', [
                'contact_id' => $contact->id,
                'admin_id' => Auth::id(),
            ]);

            return redirect()
                ->route('admin.contacts.show', $contact)
                ->with('success', 'Reply sent successfully!');

        } catch (\Exception $e) {
            Log::error('Error replying to contact:', [
                'error' => $e->getMessage(),
                'contact_id' => $contact->id,
            ]);

            return back()->with('error', 'Failed to send reply. Please try again.');
        }
    }

    /**
     * Update contact status
     */
    public function updateStatus(ContactStatusUpdateRequest $request, Contact $contact)
    {
        try {
            $contact->update(['status' => $request->status]);

            Log::info('Contact status updated:', [
                'contact_id' => $contact->id,
                'new_status' => $request->status,
            ]);

            return redirect()
                ->route('admin.contacts.show', $contact)
                ->with('success', 'Status updated successfully!');

        } catch (\Exception $e) {
            Log::error('Error updating contact status:', [
                'error' => $e->getMessage(),
                'contact_id' => $contact->id,
            ]);

            return back()->with('error', 'Failed to update status.');
        }
    }

    /**
     * Remove the specified contact message
     */
    public function destroy(Contact $contact)
    {
        try {
            $contactId = $contact->id;
            $contact->delete();

            Log::info('Contact message deleted:', ['contact_id' => $contactId]);

            return redirect()
                ->route('admin.contacts.index')
                ->with('success', 'Contact message deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Error deleting contact:', [
                'error' => $e->getMessage(),
                'contact_id' => $contact->id,
            ]);

            return back()->with('error', 'Failed to delete contact message.');
        }
    }
}
