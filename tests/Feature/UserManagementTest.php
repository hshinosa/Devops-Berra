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

// ==================== Additional tests for coverage ====================

test('super admin dapat melihat halaman create user', function () {
    $response = $this->actingAs($this->superAdmin)
        ->get(route('users.create'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page->component('Users/Create'));
});

test('super admin dapat melihat detail user', function () {
    $user = User::create([
        'name' => 'Detail User',
        'email' => 'detail@example.com',
        'phoneNumber' => '081234567890',
        'storeName' => 'Toko Detail',
        'storeAddress' => 'Jl. Detail No. 1',
        'passwordHash' => bcrypt('password'),
        'kodeQR' => 'QR-TEST1234',
    ]);

    $response = $this->actingAs($this->superAdmin)
        ->get(route('users.show', $user->id));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page->component('Users/Show')
        ->where('user.id', $user->id)
        ->where('user.name', 'Detail User')
    );
});

test('super admin dapat melihat halaman edit user', function () {
    $user = User::create([
        'name' => 'Edit User',
        'email' => 'edit@example.com',
        'phoneNumber' => '081234567891',
        'storeName' => 'Toko Edit',
        'storeAddress' => 'Jl. Edit No. 1',
        'passwordHash' => bcrypt('password'),
    ]);

    $response = $this->actingAs($this->superAdmin)
        ->get(route('users.edit', $user->id));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page->component('Users/Edit')
        ->where('user.id', $user->id)
    );
});

test('super admin dapat mengupdate data user', function () {
    $user = User::create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
        'phoneNumber' => '081234567892',
        'storeName' => 'Toko Lama',
        'storeAddress' => 'Jl. Lama No. 1',
        'passwordHash' => bcrypt('password'),
    ]);

    $response = $this->actingAs($this->superAdmin)
        ->put(route('users.update', $user->id), [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'phoneNumber' => '081234567893',
            'storeName' => 'Toko Baru',
            'storeAddress' => 'Jl. Baru No. 2',
        ]);

    $response->assertRedirect(route('users.show', $user->id));
    $user->refresh();
    expect($user->name)->toBe('New Name');
    expect($user->email)->toBe('new@example.com');
});

test('super admin dapat menghapus user', function () {
    $user = User::create([
        'name' => 'Delete User',
        'email' => 'delete@example.com',
        'phoneNumber' => '081234567894',
        'storeName' => 'Toko Delete',
        'storeAddress' => 'Jl. Delete No. 1',
        'passwordHash' => bcrypt('password'),
    ]);

    $response = $this->actingAs($this->superAdmin)
        ->delete(route('users.destroy', $user->id));

    $response->assertRedirect(route('users.index'));
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

test('update user gagal jika email sudah dipakai user lain', function () {
    $user1 = User::create([
        'name' => 'User One',
        'email' => 'user1@example.com',
        'phoneNumber' => '081111111111',
        'storeName' => 'Toko One',
        'storeAddress' => 'Jl. One',
        'passwordHash' => bcrypt('password'),
    ]);

    $user2 = User::create([
        'name' => 'User Two',
        'email' => 'user2@example.com',
        'phoneNumber' => '082222222222',
        'storeName' => 'Toko Two',
        'storeAddress' => 'Jl. Two',
        'passwordHash' => bcrypt('password'),
    ]);

    $response = $this->actingAs($this->superAdmin)
        ->put(route('users.update', $user1->id), [
            'name' => $user1->name,
            'email' => 'user2@example.com', // Email already taken
            'phoneNumber' => $user1->phoneNumber,
            'storeName' => $user1->storeName,
            'storeAddress' => $user1->storeAddress,
        ]);

    $response->assertSessionHasErrors('email');
});

test('sorting user berdasarkan nama ascending', function () {
    User::create([
        'name' => 'Zebra',
        'email' => 'zebra@example.com',
        'phoneNumber' => '081111111111',
        'storeName' => 'Toko Z',
        'storeAddress' => 'Jl. Z',
        'passwordHash' => bcrypt('password'),
    ]);

    User::create([
        'name' => 'Alpha',
        'email' => 'alpha@example.com',
        'phoneNumber' => '082222222222',
        'storeName' => 'Toko A',
        'storeAddress' => 'Jl. A',
        'passwordHash' => bcrypt('password'),
    ]);

    $response = $this->actingAs($this->superAdmin)
        ->get(route('users.index', ['sort_by' => 'name', 'sort_dir' => 'asc']));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page->component('Users/Index')
        ->where('filters.sort_by', 'name')
        ->where('filters.sort_dir', 'asc')
    );
});

test('filter user tanpa toko', function () {
    User::create([
        'name' => 'No Store',
        'email' => 'nostore@example.com',
        'phoneNumber' => '081111111111',
        'storeName' => '',
        'storeAddress' => '',
        'passwordHash' => bcrypt('password'),
    ]);

    User::create([
        'name' => 'Has Store',
        'email' => 'hasstore@example.com',
        'phoneNumber' => '082222222222',
        'storeName' => 'Toko Ada',
        'storeAddress' => 'Jl. Ada',
        'passwordHash' => bcrypt('password'),
    ]);

    $response = $this->actingAs($this->superAdmin)
        ->get(route('users.index', ['status' => 'no_store']));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page->component('Users/Index')
        ->where('filters.status', 'no_store')
    );
});
