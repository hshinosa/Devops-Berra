<?php

namespace App\Http\Controllers;

use App\Models\GlobalProduct;
use App\Services\AdminActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GlobalProductController extends Controller
{
    /**
     * Display a listing of the global products.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter');
        $kategori = $request->input('kategori');
        $merek = $request->input('merek');

        $query = GlobalProduct::query();

        // Apply search if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                    ->orWhere('kode_produk', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('merek', 'like', "%{$search}%");
            });
        }

        // Apply filters if provided
        if ($filter) {
            if ($filter === 'active') {
                $query->where('is_active', true);
            } elseif ($filter === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by specific kategori
        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        // Filter by specific merek
        if ($merek) {
            $query->where('merek', $merek);
        }

        // Get distinct values for filter dropdowns
        $kategoriList = GlobalProduct::select('kategori')
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        $merekList = GlobalProduct::select('merek')
            ->whereNotNull('merek')
            ->where('merek', '!=', '')
            ->distinct()
            ->orderBy('merek')
            ->pluck('merek');

        $products = $query->orderBy('nama_produk')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('GlobalProducts/Index', [
            'products' => $products,
            'filters' => [
                'search' => $search,
                'filter' => $filter,
                'kategori' => $kategori,
                'merek' => $merek,
            ],
            'filterOptions' => [
                'kategori' => $kategoriList,
                'merek' => $merekList,
            ],
        ]);
    }

    /**
     * Show the form for creating a new global product.
     */
    public function create()
    {
        return Inertia::render('GlobalProducts/Create');
    }

    /**
     * Store a newly created global product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_produk' => 'required|string|max:100|unique:global_products',
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'merek' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handling the image upload
        if ($request->hasFile('gambar_produk')) {
            try {
                // Save image with correct permissions
                $file = $request->file('gambar_produk');
                $fileName = time().'_'.$file->getClientOriginalName();
                $imagePath = $file->storeAs('global-products', $fileName, 'public');

                // Ensure public visibility
                Storage::disk('public')->setVisibility($imagePath, 'public');

                $validated['gambar_produk'] = $imagePath;
            } catch (\Exception $e) {
                // Log error
                Log::error('Error uploading product image: '.$e->getMessage());
            }
        }

        $product = GlobalProduct::create($validated);

        // Log admin activity
        AdminActivityLogger::log(
            'global_products',
            'created',
            "Membuat produk global baru: {$product->nama_produk}",
            [
                'product_id' => $product->id,
                'kode_produk' => $product->kode_produk,
                'kategori' => $product->kategori,
                'merek' => $product->merek,
            ]
        );

        return redirect()->route('global-products.index')
            ->with('success', 'Produk global berhasil ditambahkan');
    }

    /**
     * Display the specified global product.
     */
    public function show(GlobalProduct $globalProduct)
    {
        // Load user products that reference this global product
        $globalProduct->load('userProducts');

        return Inertia::render('GlobalProducts/Show', [
            'product' => $globalProduct,
        ]);
    }

    /**
     * Show the form for editing the specified global product.
     */
    public function edit(GlobalProduct $globalProduct)
    {
        return Inertia::render('GlobalProducts/Edit', [
            'product' => $globalProduct,
        ]);
    }

    /**
     * Update the specified global product in storage.
     */
    public function update(Request $request, GlobalProduct $globalProduct)
    {
        // Validate request
        $validatedData = $request->validate([
            'kode_produk' => 'required|string|max:100|unique:global_products,kode_produk,'.$globalProduct->id,
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'merek' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('gambar_produk')) {
            try {
                // Delete old image if exists
                if ($globalProduct->gambar_produk) {
                    Storage::disk('public')->delete($globalProduct->gambar_produk);
                }

                // Save new image
                $file = $request->file('gambar_produk');
                $fileName = time().'_'.$file->getClientOriginalName();
                $imagePath = $file->storeAs('global-products', $fileName, 'public');

                // Ensure public visibility
                Storage::disk('public')->setVisibility($imagePath, 'public');

                $validatedData['gambar_produk'] = $imagePath;
            } catch (\Exception $e) {
                Log::error('Error uploading product image: '.$e->getMessage());
            }
        }

        // Update the product
        $globalProduct->update($validatedData);

        // Log admin activity
        AdminActivityLogger::log(
            'global_products',
            'updated',
            "Memperbarui produk global: {$globalProduct->nama_produk}",
            [
                'product_id' => $globalProduct->id,
                'kode_produk' => $globalProduct->kode_produk,
                'kategori' => $globalProduct->kategori,
                'merek' => $globalProduct->merek,
            ]
        );

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Remove the specified global product from storage.
     */
    public function destroy(GlobalProduct $globalProduct)
    {
        // Store product info for logs before deletion
        $productInfo = [
            'id' => $globalProduct->id,
            'nama' => $globalProduct->nama_produk,
            'kode' => $globalProduct->kode_produk,
        ];

        // Delete image if exists
        if ($globalProduct->gambar_produk) {
            Storage::disk('public')->delete($globalProduct->gambar_produk);
        }

        // Delete the product
        $globalProduct->delete();

        // Log admin activity
        AdminActivityLogger::log(
            'global_products',
            'deleted',
            "Menghapus produk global: {$productInfo['nama']}",
            [
                'product_id' => $productInfo['id'],
                'kode_produk' => $productInfo['kode'],
            ]
        );

        return redirect()->route('products.index')
            ->with('success', 'Produk global berhasil dihapus');
    }

    /**
     * Verify a product code against global products
     *
     * @return JsonResponse
     */
    public function verifyProductCode(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|string|max:100',
        ]);

        $kodeProduct = $request->input('kode_produk');
        $globalProduct = GlobalProduct::verifyProductCode($kodeProduct);

        if ($globalProduct) {
            return response()->json([
                'success' => true,
                'message' => 'Kode produk valid dan terverifikasi',
                'product' => $globalProduct,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kode produk tidak valid atau tidak aktif',
        ]);
    }
}
