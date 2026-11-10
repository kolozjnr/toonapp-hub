<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ContactController extends Controller
{
    /**
     * Store a message from the Contact page so it lands in the admin inbox.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:120'],
            'topic' => ['required', Rule::in(Message::TOPICS)],
            'message' => ['required', 'string', 'min:5', 'max:2000'],
        ], [
            'name.required' => 'Add your name.',
            'email.required' => 'Add a valid email so we can reply.',
            'email.email' => 'Add a valid email so we can reply.',
            'topic.required' => 'Choose one of the topics from the list.',
            'topic.in' => 'Choose one of the topics from the list.',
            'message.required' => 'Write your message.',
            'message.min' => 'Write your message.',
        ]);

        Message::create([
            'name' => trim($validated['name']),
            'email' => trim($validated['email']),
            'topic' => $validated['topic'],
            'message' => trim($validated['message']),
        ]);

        return redirect()->route('contact')->with('toast', "Message sent! We'll reply by email.");
    }
}
