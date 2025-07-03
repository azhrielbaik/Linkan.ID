<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required|email',
            'message' => 'required',
        ]);

        // Kirim email
        Mail::to('dhefajar0410@gmail.com')->send(new ContactMail($request->all()));

        return back()->with('success', 'Your message has been sent successfully!');
    }
}

