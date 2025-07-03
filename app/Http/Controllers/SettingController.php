<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Mengarahkan ke view setting.blade.php
        return view('homeadminS.setting');
    }
}