<?php

use App\Models\Admin;
use App\Models\User;

beforeEach(function () {
    $this->superAdmin = Admin::factory()->superAdmin()->create();
    $this->withoutVite();
});

test('super admin dapat melihat halaman daftar user', function () {
    $response = $this->actingAs($this->superAdmin)
        ->get(route('users.index'));

    $response->assertStatus(200);
});

test('super admin dapat membuat user baru dengan data valid', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('users.store'), [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'phoneNumber' => '081234567890',
            'storeName' => 'Toko Test',
            'storeAddress' => 'Jl. Test No. 1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseHas('users', [
        'email' => 'testuser@example.com',
        'storeName' => 'Toko Test',
    ]);
});

test('tambah user gagal jika email kosong', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('users.store'), [
            'name' => 'Test User',
            'email' => '',
            'phoneNumber' => '081234567890',
            'storeName' => 'Toko Test',
            'storeAddress' => 'Jl. Test No. 1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

    $response->assertSessionHasErrors('email');
});

test('tambah user gagal jika email tidak valid', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('users.store'), [
            'name' => 'Test User',
            'email' => 'bukan-email',
            'phoneNumber' => '081234567890',
            'storeName' => 'Toko Test',
            'storeAddress' => 'Jl. Test No. 1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

    $response->assertSessionHasErrors('email');
});

test('tambah user gagal jika email sudah terdaftar', function () {
    User::create([
        'name' => 'Existing',
        'email' => 'duplicate@example.com',
        'phoneNumber' => '081111111111',
        'storeName' => 'Toko Lama',
        'storeAddress' => 'Jl. Lama',
        'passwordHash' => bcrypt('password'),
    ]);

    $response = $this->actingAs($this->superAdmin)
        ->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'duplicate@example.com',
            'phoneNumber' => '082222222222',
            'storeName' => 'Toko Baru',
            'storeAddress' => 'Jl. Baru',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

    $response->assertSessionHasErrors('email');
});

test('tambah user gagal jika password tidak dikonfirmasi', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('users.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phoneNumber' => '081234567890',
            'storeName' => 'Toko Test',
            'storeAddress' => 'Jl. Test No. 1',
            'password' => 'password123',
            'password_confirmation' => 'berbeda123',
        ]);

    $response->assertSessionHasErrors('password');
});

test('tambah user gagal jika nomor telepon format salah', function () {
    $response = $this->actingAs($this->superAdmin)
        ->post(route('users.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phoneNumber' => 'abc',
            'storeName' => 'Toko Test',
            'storeAddress' => 'Jl. Test No. 1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

    $response->assertSessionHasErrors('phoneNumber');
});

test('search user berdasarkan nama', function () {
    User::create([
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'phoneNumber' => '081111111111',
        'storeName' => 'Toko Budi',
        'storeAddress' => 'Jl. Budi',
        'passwordHash' => bcrypt('password'),
    ]);

    User::create([
        'name' => 'Siti Aminah',
        'email' => 'siti@example.com',
        'phoneNumber' => '082222222222',
        'storeName' => 'Toko Siti',
        'storeAddress' => 'Jl. Siti',
        'passwordHash' => bcrypt('password'),
    ]);

    $response = $this->actingAs($this->superAdmin)
        ->get(route('users.index', ['search' => 'Budi']));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page->component('Users/Index')
        ->has('users.data', 1)
        ->where('users.data.0.name', 'Budi Santoso')
    );
});

test('filter user berdasarkan status toko', function () {
    User::create([
        'name' => 'Punya Toko',
        'email' => 'toko@example.com',
        'phoneNumber' => '081111111111',
        'storeName' => 'Toko ABC',
        'storeAddress' => 'Jl. ABC',
        'passwordHash' => bcrypt('password'),
    ]);

    $response = $this->actingAs($this->superAdmin)
        ->get(route('users.index', ['status' => 'has_store']));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page->component('Users/Index')
        ->where('filters.status', 'has_store')
    );
});
