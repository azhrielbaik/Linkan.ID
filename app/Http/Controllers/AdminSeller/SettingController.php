<?php

namespace App\Http\Controllers\AdminSeller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin_seller.features.settings.index');
    }
}
