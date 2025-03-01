<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('guest.contact.index');
    }

    public function send(Request $request)
    {
        $details = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        Mail::to(env('KIM_MAIL'))->send(new ContactFormMail($details));

        // Send email to the .env sender
        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new ContactFormMail($details));

        return back()->with('success', 'Your message has been sent successfully!');
    }
}
