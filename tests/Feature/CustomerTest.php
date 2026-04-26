<?php

use App\Models\Admin;
use App\Models\Customer;
use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
    $this->admin = Admin::factory()->create();
    $this->user = User::create([
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'phoneNumber' => '081234567890',
        'storeName' => 'Toko Test',
        'storeAddress' => 'Alamat Test',
        'passwordHash' => bcrypt('password'),
    ]);
});

test('customer page is displayed', function () {
    $this->actingAs($this->admin)
        ->get('/customers')
        ->assertStatus(200);
});

test('can create customer', function () {
    $this->actingAs($this->admin)
        ->post('/customers', [
            'nama_pelanggan' => 'Budi Santoso',
            'nomor_telepon' => '08123456789',
        ])
        ->assertRedirect('/customers')
        ->assertSessionHas('success');

    $this->assertDatabaseHas('customers', [
        'nama_pelanggan' => 'Budi Santoso',
        'nomor_telepon' => '08123456789',
    ]);
});

test('cannot create customer without name', function () {
    $this->actingAs($this->admin)
        ->post('/customers', [
            'nama_pelanggan' => '',
            'nomor_telepon' => '08123456789',
        ])
        ->assertSessionHasErrors('nama_pelanggan');

    $this->assertDatabaseMissing('customers', [
        'nomor_telepon' => '08123456789',
    ]);
});

test('can search customer', function () {
    Customer::create([
        'nama_pelanggan' => 'Budi',
        'nomor_telepon' => '08123456789',
        'user_id' => $this->user->id,
    ]);

    Customer::create([
        'nama_pelanggan' => 'Agus',
        'nomor_telepon' => '08987654321',
        'user_id' => $this->user->id,
    ]);

    $this->actingAs($this->admin)
        ->get('/customers?search=Budi')
        ->assertStatus(200);
});
