<?php

use App\Models\Admin;
use App\Models\GlobalProduct;

test('admin can create global product', function () {
    $admin = Admin::factory()->superAdmin()->create();

    $this->actingAs($admin)
        ->withoutMiddleware()
        ->post(route('global-products.store'), [
            'kode_produk' => 'GP001',
            'nama_produk' => 'Produk Global Test',
            'kategori' => 'Elektronik',
            'merek' => 'Sony',
            'deskripsi' => 'Deskripsi produk global',
            'is_active' => true,
        ])
        ->assertRedirect(route('global-products.index'));

    $this->assertDatabaseHas('global_products', [
        'kode_produk' => 'GP001',
        'nama_produk' => 'Produk Global Test',
    ]);
});

test('admin cannot create global product without required fields', function () {
    $admin = Admin::factory()->create();

    $this->actingAs($admin)
        ->withoutMiddleware()
        ->post(route('global-products.store'), [
            'kode_produk' => 'GP002',
            'merek' => 'Test',
        ])
        ->assertRedirect();

    $this->assertDatabaseMissing('global_products', [
        'kode_produk' => 'GP002',
    ]);
});

test('admin cannot create global product with duplicate kode_produk', function () {
    $admin = Admin::factory()->create();

    GlobalProduct::create([
        'kode_produk' => 'UNIQUE-001',
        'nama_produk' => 'Existing',
        'kategori' => 'Test',
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->withoutMiddleware()
        ->post(route('global-products.store'), [
            'kode_produk' => 'UNIQUE-001',
            'nama_produk' => 'Duplicate',
            'kategori' => 'Test2',
            'is_active' => true,
        ])
        ->assertSessionHasErrors(['kode_produk']);
});

test('admin can list and filter global products by kategori', function () {
    $admin = Admin::factory()->create();

    GlobalProduct::create([
        'kode_produk' => 'GP001',
        'nama_produk' => 'Produk A',
        'kategori' => 'Elektronik',
        'merek' => 'Sony',
        'is_active' => true,
    ]);
    GlobalProduct::create([
        'kode_produk' => 'GP002',
        'nama_produk' => 'Produk B',
        'kategori' => 'Pakaian',
        'merek' => 'Nike',
        'is_active' => true,
    ]);
    GlobalProduct::create([
        'kode_produk' => 'GP003',
        'nama_produk' => 'Produk C',
        'kategori' => 'Elektronik',
        'merek' => 'Samsung',
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->withoutMiddleware()
        ->get(route('global-products.index', ['kategori' => 'Elektronik']))
        ->assertOk()
        ->assertSee('Produk A')
        ->assertSee('Produk C')
        ->assertDontSee('Produk B');
});

test('admin can filter global products by merek', function () {
    $admin = Admin::factory()->create();

    GlobalProduct::create([
        'kode_produk' => 'GP004',
        'nama_produk' => 'Produk D',
        'kategori' => 'Elektronik',
        'merek' => 'Sony',
        'is_active' => true,
    ]);
    GlobalProduct::create([
        'kode_produk' => 'GP005',
        'nama_produk' => 'Produk E',
        'kategori' => 'Pakaian',
        'merek' => 'Adidas',
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->withoutMiddleware()
        ->get(route('global-products.index', ['merek' => 'Sony']))
        ->assertOk()
        ->assertSee('Produk D')
        ->assertDontSee('Produk E');
});
