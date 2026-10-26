<?php

namespace App\Http\Controllers;

use App\Models\Appearance;
use App\Models\Shortlink;
use App\Services\ShortlinkService;
use Illuminate\Http\Request;

class PublicShortlinkController extends Controller
{
    protected ShortlinkService $shortlinkService;

    public function __construct(ShortlinkService $shortlinkService)
    {
        $this->shortlinkService = $shortlinkService;
    }

    public function redirect(Request $request, $slug)
    {
        $shortlink = Shortlink::where('slug', $slug)->first();

        if (! $shortlink) {
            $appearance = Appearance::where('alias', $slug)->first();
            if ($appearance) {
                return app(PublicPageController::class)->show($slug);
            }
            abort(404, 'Tautan tidak ditemukan.');
        }

        if ($shortlink->expires_at && now()->greaterThan($shortlink->expires_at)) {
            abort(410, 'Tautan ini telah kedaluwarsa.');
        }

        if ($shortlink->password) {
            $sessionKey = 'unlocked_shortlink_'.$shortlink->id;
            if (! session()->has($sessionKey)) {
                return redirect()->route('shortlink.password.form', ['slug' => $slug]);
            }
        }

        return $this->shortlinkService->recordClick($shortlink, $request);
    }

    public function passwordForm($slug)
    {
        $shortlink = Shortlink::where('slug', $slug)->firstOrFail();

        if ($shortlink->expires_at && now()->greaterThan($shortlink->expires_at)) {
            abort(410, 'Tautan ini telah kedaluwarsa.');
        }

        return view('public.shortlinks.password', compact('shortlink'));
    }

    public function verifyPassword(Request $request, $slug)
    {
        $shortlink = Shortlink::where('slug', $slug)->firstOrFail();

        $request->validate(['password' => 'required']);

        if ($request->password === $shortlink->password) {
            session(['unlocked_shortlink_'.$shortlink->id => true]);

            return redirect('/'.$slug);
        }

        return back()->withErrors(['password' => 'Kata sandi salah.']);
    }
}
