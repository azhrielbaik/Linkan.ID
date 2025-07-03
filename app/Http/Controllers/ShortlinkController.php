<?php

namespace App\Http\Controllers;

use App\Models\Shortlink;
use Illuminate\Http\Request;

class ShortlinkController extends Controller
{
    public function create()
    {
        return view('shortlink.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|alpha_dash|unique:shortlinks,slug',
            'destination' => 'required|url',
        ]);

        Shortlink::create([
            'slug' => $request->slug,
            'destination' => $request->destination,
        ]);

       // return back()->with('success', 'Shortlink berhasil dibuat: https://Linkan.id/' . $request->slug);
       
      //untuk lokal host
        return back()
        ->with('success', 'Shortlink berhasil dibuat: ' . url('/' . $request->slug))
        ->withInput();
    
    }

    public function redirect($slug)
    {
        $shortlink = Shortlink::where('slug', $slug)->firstOrFail();
        return redirect($shortlink->destination);
    }

    public function index()
    {
        $shortlinks = Shortlink::orderBy('created_at', 'desc')->paginate(5);
        return view('shortlink.create', compact('shortlinks'));
    }
}
