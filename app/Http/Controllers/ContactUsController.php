<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsRequest;
use App\Models\ContactUs;
use App\Models\Feature;
use App\Models\Subscription;
use Inertia\Inertia;

class ContactUsController extends Controller
{
    public function index()
    {
        return inertia('contact/index', [
            'messages' => ContactUs::query()
                ->where('company_id',auth()->user()->company_id)
                ->latest()
                ->paginate(15)
                ->through(fn($m) => [
                    'id' => $m->id,
                    'department' => $m->department,
                    'subject' => $m->subject,
                    'name' => $m->name,
                    'email' => $m->email,
                    'message' => $m->message,
                    'attachment_path' => $m->attachment_path,
                    'created_at' => $m->created_at?->toDateTimeString(),
                ]),
            'can' => ['delete' => auth()->user()?->can('delete contact message') ?? false],
        ]);

    }

    public function store(ContactUsRequest $request)
    {
        $data = $request->validated();

        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('contact_attachments', 'public');
        }

        ContactUs::create([
            'company_id'      => $data['company_id'] ?? null,
            'department'      => $data['department'],
            'subject'         => $data['subject'] ?? null,
            'name'            => $data['name'],
            'email'           => $data['email'],
            'message'         => $data['message'],
            'attachment_path' => $path,
            'ip'              => $request->ip(),
            'user_agent'      => (string) $request->userAgent(),
        ]);

        return back()->with('success', 'Thanks! Your message has been received.');
    }

    public function destroy(ContactUs $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('contact-matrix.index')
            ->with('success', 'Contact message deleted.');
    }
}
