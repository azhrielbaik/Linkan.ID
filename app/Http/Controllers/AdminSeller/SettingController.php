<?php

namespace App\Http\Controllers\AdminSeller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        return view('admin_seller.features.settings.index', compact('user'));
    }
}
