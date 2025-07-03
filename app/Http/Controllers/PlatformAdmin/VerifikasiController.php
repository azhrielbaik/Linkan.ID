<?php

namespace App\Http\Controllers\PlatformAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DigitalProduct;

class VerifikasiController extends Controller
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
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500'
        ]);

        $product = DigitalProduct::findOrFail($id);
        $product->verification_status = $request->status;
        
        if ($request->status === 'rejected') {
            $product->rejection_reason = $request->rejection_reason;
        } else {
            $product->rejection_reason = null;
        }
        
        $product->save();

        return redirect()->back()->with('success', 'Status verifikasi produk berhasil diperbarui');
    }
}
