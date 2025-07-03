<?php

namespace App\Http\Controllers;

use App\Models\DigitalProduct;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index()
    {
        $products = DigitalProduct::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('platformadmin.verifikasi', compact('products'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $product = DigitalProduct::findOrFail($id);
        $product->verification_status = $request->status;
        $product->save();

        return redirect()->back()->with('success', 'Status verifikasi produk berhasil diperbarui');
    }
} 