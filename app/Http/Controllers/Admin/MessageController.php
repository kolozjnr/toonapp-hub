<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Show every message from the Contact page, newest first.
     */
    public function index(): View
    {
        $messages = Message::query()->latest()->get();

        return view('admin.messages', [
            'messages' => $messages,
            'unreadCount' => $messages->filter(fn (Message $message): bool => $message->isUnread())->count(),
            'totalCount' => $messages->count(),
        ]);
    }

    /**
     * Record that the admin has answered a message, or undo it.
     */
    public function updateRead(Request $request, Message $message): RedirectResponse
    {
        $request->validate([
            'read' => ['required', 'boolean'],
        ]);

        $isRead = $request->boolean('read');

        $message->update(['read_at' => $isRead ? now() : null]);

        return back()->with('toast', $isRead ? 'Message marked as read' : 'Message marked as unread');
    }

    /**
     * Drop a message from the inbox. The sender keeps their own copy.
     */
    public function destroy(Message $message): RedirectResponse
    {
        $message->delete();

        return back()->with('success', 'Message deleted.');
    }
}
