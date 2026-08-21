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

        // Catat Log Aktivitas
        $action = $request->status === 'approved' ? 'approve_product' : 'reject_product';
        $desc = $request->status === 'approved' 
            ? "Menyetujui verifikasi produk: {$product->title} (Seller: " . ($product->user->name ?? 'User') . ")"
            : "Menolak verifikasi produk: {$product->title} (Alasan: {$request->rejection_reason})";

        \App\Services\ActivityLogger::log($action, $desc, [
            'product_id' => $product->id,
            'product_title' => $product->title,
            'seller_id' => $product->user_id,
            'status' => $request->status,
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->back()->with('success', 'Status verifikasi produk berhasil diperbarui');
    }
}
