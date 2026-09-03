<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function index()
    {
        return view('public.pages.contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'name'       => 'nullable|string|max:255',
            'email'      => 'required|email',
            'phone'      => 'nullable|string|max:50',
            'message'    => 'required|string',
        ]);

        $data = $request->all();

        // Buat field name dari first_name & last_name jika ada
        $fullName = trim(($request->first_name ?? '') . ' ' . ($request->last_name ?? ''));
        if (empty($fullName)) {
            $fullName = $request->name ?? 'User';
        }
        $data['name'] = $fullName;

        try {
            // Kirim email
            Mail::to('sense.xj@gmail.com')->send(new ContactMail($data));

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Contact mail sending error: ' . $e->getMessage());
        }

        return back()->with('success', 'Your message has been sent successfully!');
    }
}
