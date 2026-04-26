<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\GlobalProduct;
use App\Models\Product;
use App\Models\User;
use App\Services\AdminActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     * For non-admin users, shows only the catalog (global products) and their own products.
     * For admin users, shows all products with the option to filter by user.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter');
        $scope = $request->input('scope', 'all'); // 'all', 'catalog', 'my'

        $query = Product::query();

        $currentUser = auth()->user();
        $isAdmin = $currentUser instanceof Admin && $currentUser->is_super_admin;

        // Apply scope
        if ($scope === 'catalog') {
            $query->catalog(); // Only global catalog products
        } elseif ($scope === 'my') {
            $query->ownedBy(auth()->id()); // Only current user's products
        } else {
            // For regular users: show only global products and their own
            if (! $isAdmin) {
                $query->where(function ($q) {
                    $q->whereNull('user_id')
                        ->orWhere('user_id', auth()->id());
                });
            }
            // For admin: show all products
        }

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
            } elseif (in_array($filter, ['kategori', 'merek'])) {
                $query->whereNotNull($filter)->orderBy($filter);
            }
        }

        $products = $query->orderByDesc('created_at')
            ->orderBy('nama_produk')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'filters' => [
                'search' => $search,
                'filter' => $filter,
            ],
        ]);
    }

    /**
     * Show the form for creating a new product.
     * Admin creates inventory product for a selected store.
     */
    public function create()
    {
        // Get list of stores (users) for inventory assignment
        $users = User::orderBy('storeName')
            ->select(['id', 'name', 'storeName'])
            ->get();

        // Get active global products for reference
        $catalogProducts = GlobalProduct::where('is_active', true)
            ->orderBy('nama_produk')
            ->get(['id', 'kode_produk', 'nama_produk', 'kategori', 'merek', 'deskripsi', 'gambar_produk']);

        return Inertia::render('Products/Create', [
            'users' => $users,
            'catalogProducts' => $catalogProducts,
        ]);
    }

    /**
     * Store a newly created product in storage.
     * Admin creates inventory product for a store (user).
     * Optionally references a global product for data inheritance.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'global_product_id' => 'nullable|exists:global_products,id',
            'kode_produk' => 'nullable|string|max:100',
            'nama_produk' => 'required_without:global_product_id|string|max:255',
            'jumlah_produk' => 'required|integer|min:0',
            'harga_modal' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'kategori' => 'nullable|string|max:255',
            'merek' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (! empty($validated['kode_produk']) && Product::where('user_id', $validated['user_id'])
            ->where('kode_produk', $validated['kode_produk'])
            ->exists()) {
            return back()
                ->withErrors([
                    'kode_produk' => 'Kode produk sudah digunakan untuk toko ini.',
                ])
                ->withInput();
        }

        // If a global product is selected, fill missing fields from it
        if (! empty($validated['global_product_id'])) {
            $globalProduct = GlobalProduct::find($validated['global_product_id']);
            if ($globalProduct) {
                if (empty($validated['kode_produk'])) {
                    $validated['kode_produk'] = $globalProduct->kode_produk;
                }
                if (empty($validated['nama_produk'])) {
                    $validated['nama_produk'] = $globalProduct->nama_produk;
                }
                if (empty($validated['kategori'])) {
                    $validated['kategori'] = $globalProduct->kategori;
                }
                if (empty($validated['merek'])) {
                    $validated['merek'] = $globalProduct->merek;
                }
                if (empty($validated['deskripsi'])) {
                    $validated['deskripsi'] = $globalProduct->deskripsi;
                }
                // Use global product image if not uploading a new one
                if (! $request->hasFile('gambar_produk') && $globalProduct->gambar_produk) {
                    $validated['gambar_produk'] = $globalProduct->gambar_produk;
                }
            }
        }

        // Image upload handling
        if ($request->hasFile('gambar_produk')) {
            try {
                $file = $request->file('gambar_produk');
                $fileName = time().'_'.$file->getClientOriginalName();
                $imagePath = $file->storeAs('products', $fileName, 'public');
                Storage::disk('public')->setVisibility($imagePath, 'public');
                $validated['gambar_produk'] = $imagePath;
            } catch (\Exception $e) {
                Log::error('Error uploading product image: '.$e->getMessage());
            }
        }

        $product = Product::create($validated);

        // Log admin activity for inventory addition
        AdminActivityLogger::log(
            'products',
            'created',
            "Menambah produk ke inventory: {$product->nama_produk} (Store ID: {$product->user_id})",
            [
                'product_id' => $product->id,
                'kode_produk' => $product->kode_produk,
                'global_product_id' => $product->global_product_id,
                'user_id' => $product->user_id,
                'jumlah_produk' => $product->jumlah_produk,
                'harga_jual' => $product->harga_jual,
            ]
        );

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan ke inventory');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        // Note: Transactions don't have direct product relationship
        // Products are stored in transactions.detail_items (JSON)
        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', [
            'product' => $product,
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Validate request
        $validatedData = $request->validate([
            'kode_produk' => 'required|string|max:100|unique:products,kode_produk,'.$product->id,
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'merek' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('gambar_produk')) {
            try {                // Delete old image if exists
                if ($product->gambar_produk) {
                    Storage::disk('public')->delete($product->gambar_produk);
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
        $product->update($validatedData);

        // Log admin activity
        AdminActivityLogger::log(
            'products',
            'updated',
            "Memperbarui produk: {$product->nama_produk}",
            [
                'product_id' => $product->id,
                'kode_produk' => $product->kode_produk,
                'kategori' => $product->kategori,
                'merek' => $product->merek,
            ]
        );

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {        // Store product info for logs before deletion
        $productInfo = [
            'id' => $product->id,
            'nama' => $product->nama_produk,
            'kode' => $product->kode_produk,
        ];

        // Delete image if exists
        if ($product->gambar_produk) {
            Storage::disk('public')->delete($product->gambar_produk);
        }

        // Delete the product
        $product->delete();

        // Log admin activity
        AdminActivityLogger::log(
            'products',
            'deleted',
            "Menghapus produk: {$productInfo['nama']}",
            [
                'product_id' => $productInfo['id'],
                'kode_produk' => $productInfo['kode'],
            ]
        );

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus');
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
