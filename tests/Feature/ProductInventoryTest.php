<?php

use App\Models\Admin;
use App\Models\GlobalProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('admin can add global product to inventory for a store', function () {
    $storeUser = User::create([
        'name' => 'Toko Sejahtera',
        'email' => 'toko@example.com',
        'phoneNumber' => '08123456789',
        'storeName' => 'Toko Sejahtera',
        'storeAddress' => 'Jl. Merdeka No.1',
        'passwordHash' => Hash::make('password'),
        'is_active' => true,
    ]);

    $admin = Admin::factory()->superAdmin()->create();

    $global = GlobalProduct::create([
        'kode_produk' => 'GLOBAL-001',
        'nama_produk' => 'Produk Global Test',
        'kategori' => 'Makanan',
        'merek' => 'Indofood',
        'deskripsi' => 'Deskripsi produk global',
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->withoutMiddleware()
        ->post(route('products.store'), [
            'user_id' => $storeUser->id,
            'global_product_id' => $global->id,
            'jumlah_produk' => 50,
            'harga_modal' => 10000,
            'harga_jual' => 15000,
        ])
        ->assertRedirect(route('products.index'));

    $this->assertDatabaseHas('products', [
        'user_id' => $storeUser->id,
        'global_product_id' => $global->id,
        'kode_produk' => 'GLOBAL-001',
        'nama_produk' => 'Produk Global Test',
        'jumlah_produk' => 50,
        'harga_modal' => 10000,
        'harga_jual' => 15000,
    ]);
});

test('admin can verify global product code via API', function () {
    $admin = Admin::factory()->create();

    $global = GlobalProduct::create([
        'kode_produk' => 'VERIFY-001',
        'nama_produk' => 'Produk Verifikasi',
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->withoutMiddleware()
        ->post(route('products.verify-code'), [
            'kode_produk' => 'VERIFY-001',
        ])
        ->assertJson([
            'success' => true,
        ])
        ->assertJsonFragment([
            'kode_produk' => 'VERIFY-001',
        ]);
});

test('admin can create inventory product without global product reference', function () {
    $storeUser = User::create([
        'name' => 'Toko Melati',
        'email' => 'melati@example.com',
        'phoneNumber' => '08987654321',
        'storeName' => 'Toko Melati',
        'storeAddress' => 'Jl. Mawar',
        'passwordHash' => Hash::make('password'),
        'is_active' => true,
    ]);

    $admin = Admin::factory()->create();

    $this->actingAs($admin)
        ->withoutMiddleware()
        ->post(route('products.store'), [
            'user_id' => $storeUser->id,
            'kode_produk' => 'MANUAL-001',
            'nama_produk' => 'Produk Manual',
            'jumlah_produk' => 100,
            'harga_modal' => 5000,
            'harga_jual' => 7500,
            'kategori' => 'Peralatan',
            'merek' => 'Local',
        ])
        ->assertRedirect(route('products.index'));

    $this->assertDatabaseHas('products', [
        'user_id' => $storeUser->id,
        'kode_produk' => 'MANUAL-001',
        'nama_produk' => 'Produk Manual',
        'jumlah_produk' => 100,
    ]);
});

test('admin can see inventory products for any store on the products index', function () {
    $storeUser = User::create([
        'name' => 'Toko Terang',
        'email' => 'terang@example.com',
        'phoneNumber' => '08111111111',
        'storeName' => 'Toko Terang',
        'storeAddress' => 'Jl. Melati No. 2',
        'passwordHash' => Hash::make('password'),
        'is_active' => true,
    ]);

    $admin = Admin::factory()->superAdmin()->create();

    $globalProduct = GlobalProduct::create([
        'kode_produk' => 'GLOBAL-SEE-001',
        'nama_produk' => 'Produk Yang Harus Terlihat',
        'kategori' => 'Elektronik',
        'merek' => 'Testing',
        'is_active' => true,
    ]);

    $createdProduct = Product::create([
        'user_id' => $storeUser->id,
        'global_product_id' => $globalProduct->id,
        'kode_produk' => $globalProduct->kode_produk,
        'nama_produk' => $globalProduct->nama_produk,
        'jumlah_produk' => 12,
        'harga_modal' => 5000,
        'harga_jual' => 7500,
        'kategori' => $globalProduct->kategori,
        'merek' => $globalProduct->merek,
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->withoutMiddleware()
        ->get(route('products.index'))
        ->assertOk()
        ->assertSee($createdProduct->kode_produk)
        ->assertSee($createdProduct->nama_produk);
});

test('admin cannot create duplicate product code for the same store', function () {
    $storeUser = User::create([
        'name' => 'Toko Kuning',
        'email' => 'kuning@example.com',
        'phoneNumber' => '08222222222',
        'storeName' => 'Toko Kuning',
        'storeAddress' => 'Jl. Anggrek No. 3',
        'passwordHash' => Hash::make('password'),
        'is_active' => true,
    ]);

    $admin = Admin::factory()->superAdmin()->create();

    GlobalProduct::create([
        'kode_produk' => 'MYK2025',
        'nama_produk' => 'Minyak Sawit Prabowo',
        'kategori' => 'Kebutuhan Makan',
        'merek' => 'Sawit Asli Prabowo',
        'deskripsi' => 'Dari Minyak Sawit Prabowo Asli',
        'is_active' => true,
    ]);

    Product::create([
        'user_id' => $storeUser->id,
        'kode_produk' => 'MYK2025',
        'nama_produk' => 'Minyak Sawit Prabowo',
        'jumlah_produk' => 10,
        'harga_modal' => 1000000,
        'harga_jual' => 1200000,
        'kategori' => 'Kebutuhan Makan',
        'merek' => 'Sawit Asli Prabowo',
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->withoutMiddleware()
        ->post(route('products.store'), [
            'user_id' => $storeUser->id,
            'kode_produk' => 'MYK2025',
            'nama_produk' => 'Minyak Sawit Prabowo Baru',
            'jumlah_produk' => 12,
            'harga_modal' => 1200000,
            'harga_jual' => 240000,
            'kategori' => 'Kebutuhan Makan',
            'merek' => 'Sawit Asli Prabowo',
            'deskripsi' => 'Dari Minyak Sawit Prabowo Asli',
        ])
        ->assertSessionHasErrors(['kode_produk']);

    $this->assertDatabaseCount('products', 1);
});

test('admin sees newest inventory product first on products index', function () {
    $storeUser = User::create([
        'name' => 'Toko Baru',
        'email' => 'barutoko@example.com',
        'phoneNumber' => '08333333333',
        'storeName' => 'Toko Baru',
        'storeAddress' => 'Jl. Kenanga No. 4',
        'passwordHash' => Hash::make('password'),
        'is_active' => true,
    ]);

    $admin = Admin::factory()->superAdmin()->create();

    $oldProduct = Product::create([
        'user_id' => $storeUser->id,
        'kode_produk' => 'OLD-001',
        'nama_produk' => 'Produk Lama',
        'jumlah_produk' => 1,
        'harga_modal' => 1000,
        'harga_jual' => 1500,
        'is_active' => true,
    ]);

    $newProduct = Product::create([
        'user_id' => $storeUser->id,
        'kode_produk' => 'NEW-001',
        'nama_produk' => 'Produk Baru',
        'jumlah_produk' => 2,
        'harga_modal' => 2000,
        'harga_jual' => 2500,
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)
        ->withoutMiddleware()
        ->get(route('products.index'))
        ->assertOk();

    $content = $response->getContent();
    expect(strpos($content, $newProduct->nama_produk))->toBeLessThan(strpos($content, $oldProduct->nama_produk));
});

test('admin sees products from other stores on products index', function () {
    $storeOne = User::create([
        'name' => 'Toko Satu',
        'email' => 'toko-satu@example.com',
        'phoneNumber' => '08444444444',
        'storeName' => 'Toko Satu',
        'storeAddress' => 'Jl. Satu No. 1',
        'passwordHash' => Hash::make('password'),
        'is_active' => true,
    ]);

    $storeTwo = User::create([
        'name' => 'Toko Dua',
        'email' => 'toko-dua@example.com',
        'phoneNumber' => '08555555555',
        'storeName' => 'Toko Dua',
        'storeAddress' => 'Jl. Dua No. 2',
        'passwordHash' => Hash::make('password'),
        'is_active' => true,
    ]);

    $admin = Admin::factory()->superAdmin()->create();

    $visibleProduct = Product::create([
        'user_id' => $storeTwo->id,
        'kode_produk' => 'VISIBLE-001',
        'nama_produk' => 'Produk Toko Dua',
        'jumlah_produk' => 5,
        'harga_modal' => 5000,
        'harga_jual' => 7000,
        'is_active' => true,
    ]);

    $hiddenForStoreOne = Product::create([
        'user_id' => $storeOne->id,
        'kode_produk' => 'VISIBLE-002',
        'nama_produk' => 'Produk Toko Satu',
        'jumlah_produk' => 8,
        'harga_modal' => 8000,
        'harga_jual' => 9000,
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)
        ->withoutMiddleware()
        ->get(route('products.index'))
        ->assertOk();

    $content = $response->getContent();
    expect($content)->toContain($visibleProduct->nama_produk);
    expect($content)->toContain($hiddenForStoreOne->nama_produk);
});

test('admin receives json response when verifying product code', function () {
    $admin = Admin::factory()->superAdmin()->create();

    $global = GlobalProduct::create([
        'kode_produk' => 'JSON-001',
        'nama_produk' => 'Produk JSON',
        'kategori' => 'Tes',
        'merek' => 'Tes',
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->withoutMiddleware()
        ->postJson(route('products.verify-code'), [
            'kode_produk' => $global->kode_produk,
        ])
        ->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Kode produk valid dan terverifikasi',
        ])
        ->assertJsonFragment([
            'kode_produk' => 'JSON-001',
        ]);
});

test('products create page includes csrf meta tag', function () {
    $admin = Admin::factory()->superAdmin()->create();

    $this->actingAs($admin)
        ->get(route('products.create'))
        ->assertOk()
        ->assertSee('meta name="csrf-token"', false);
});
