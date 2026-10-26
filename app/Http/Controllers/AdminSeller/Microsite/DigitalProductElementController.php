<?php

namespace App\Http\Controllers\AdminSeller\Microsite;

use App\Http\Controllers\Controller;
use App\Models\Appearance;
use App\Models\DigitalProduct;
use App\Services\AdminSeller\DigitalProductService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DigitalProductElementController extends Controller
{
    public function __construct(
        protected DigitalProductService $digitalProductService
    ) {}

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'element_id' => 'nullable|integer',
        ]);

        $user = $request->user();

        // Extract and map data fields
        $pricingType = $request->pricing_type ?? 'fixed';
        $price = $pricingType === 'fixed' ? ($request->price_fixed ?? 0) : 0;

        $hasQtyLimit = ($request->has_quantity_limit === 'true' || $request->has_quantity_limit == 1);
        $isScheduled = ($request->is_scheduled === 'true' || $request->is_scheduled == 1);

        $deliverableType = $request->deliverable_type ?? $request->platform_type ?? 'link';
        $deliverableUrl = $request->deliverable_url ?? $request->platform_url;

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'pricing_type' => $pricingType,
            'price' => $price,
            'sale_price' => null,
            'price_min' => $request->price_min,
            'price_max' => $request->price_max,
            'has_quantity_limit' => $hasQtyLimit,
            'quantity' => $hasQtyLimit ? $request->quantity_max : null,
            'quantity_min' => $request->quantity_min ?? 1,
            'is_scheduled' => $isScheduled,
            'start_time' => ($isScheduled && $request->start_time) ? Carbon::parse($request->start_time) : null,
            'end_time' => ($isScheduled && $request->end_time) ? Carbon::parse($request->end_time) : null,
            'deliverable_type' => $deliverableType,
            'platform_type' => $deliverableType,
            'deliverable_url' => $deliverableUrl,
            'platform_url' => $deliverableUrl,
            'button_text' => 'Beli Sekarang',
        ];

        // Collect uploaded images (either as photos/image array or media_$i)
        $imageFiles = [];
        if ($request->hasFile('photos')) {
            $imageFiles = is_array($request->file('photos')) ? $request->file('photos') : [$request->file('photos')];
        } elseif ($request->hasFile('image')) {
            $imageFiles = [$request->file('image')];
        }

        if ($request->has('media_count')) {
            $count = (int) $request->media_count;
            for ($i = 0; $i < $count; $i++) {
                if ($request->hasFile("media_$i")) {
                    $imageFiles[] = $request->file("media_$i");
                }
            }
        }

        $platformFile = $request->file('deliverable_file') ?? $request->file('platform_file');

        try {
            if ($request->element_id) {
                $product = $this->digitalProductService->getProduct((int) $request->element_id, $user->id);
                $digitalProduct = $this->digitalProductService->updateProduct(
                    $product,
                    $data,
                    $imageFiles,
                    $platformFile,
                    $request->input('existing_media')
                );
            } else {
                $digitalProduct = $this->digitalProductService->storeProduct(
                    $data,
                    $user->id,
                    $imageFiles,
                    $platformFile,
                    $request->input('existing_media')
                );

                if ($request->filled('appearance_id')) {
                    $appearance = Appearance::where('id', $request->appearance_id)
                        ->where('user_id', $user->id)
                        ->first();
                    if ($appearance) {
                        $appearance->addBlock('digitalproduct_'.$digitalProduct->id);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'id' => $digitalProduct->id,
                'title' => $digitalProduct->title,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan produk digital element: '.$e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan produk: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $product = DigitalProduct::where('id', $id)->where('user_id', $user->id)->first();
        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $appearanceId = $request->input('appearance_id') ?? $request->query('appearance_id');
        if ($appearanceId) {
            $appearance = Appearance::where('id', $appearanceId)
                ->where('user_id', $user->id)
                ->first();

            if ($appearance) {
                $appearance->removeBlock('digitalproduct_'.$product->id);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari microsite ini.',
        ]);
    }

    public function pinExisting(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'appearance_id' => 'required|integer',
        ]);

        $user = $request->user();

        $product = DigitalProduct::where('id', $request->product_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $appearance = Appearance::where('id', $request->appearance_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $key = 'digitalproduct_'.$product->id;
        if (in_array($key, $appearance->blocks_order, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Produk ini sudah ada di microsite Anda.',
            ], 409);
        }

        $appearance->addBlock($key);

        return response()->json([
            'success' => true,
            'id' => $product->id,
            'title' => $product->title,
        ]);
    }
}
