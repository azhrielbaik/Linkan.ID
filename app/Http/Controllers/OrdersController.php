<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Orders::all();
        return view('homeadminS.orders', compact('orders'));
    }

    public function show($id)
    {
        $order = Orders::findOrFail($id);
        return view('homeadminS.orders.show', compact('order'));
    }
}

