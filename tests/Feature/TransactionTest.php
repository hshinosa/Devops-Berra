<?php

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Transaction;
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

test('transaction summary page is displayed', function () {
    $this->actingAs($this->admin)
        ->get('/transactions')
        ->assertStatus(200);
});

test('transaction summary calculates correctly', function () {
    $customer = Customer::create([
        'nama_pelanggan' => 'Budi',
        'nomor_telepon' => '08123456789',
        'user_id' => $this->user->id,
    ]);

    Transaction::create([
        'user_id' => $this->user->id,
        'id_pelanggan' => $customer->id,
        'total_belanja' => 50000,
        'tanggal_transaksi' => now(),
        'total_modal' => 30000,
        'metode_pembayaran' => 'Cash',
        'status_pembayaran' => 'Lunas',
        'detail_items' => [],
        'jumlah_bayar' => 50000,
        'jumlah_kembali' => 0,
    ]);

    Transaction::create([
        'user_id' => $this->user->id,
        'id_pelanggan' => $customer->id,
        'total_belanja' => 150000,
        'tanggal_transaksi' => now(),
        'total_modal' => 100000,
        'metode_pembayaran' => 'Cash',
        'status_pembayaran' => 'Lunas',
        'detail_items' => [],
        'jumlah_bayar' => 150000,
        'jumlah_kembali' => 0,
    ]);

    $this->actingAs($this->admin)
        ->get('/transactions')
        ->assertStatus(200);

    $customerFromDb = Customer::withCount('transactions')
        ->withSum('transactions', 'total_belanja')
        ->find($customer->id);

    expect($customerFromDb->transactions_count)->toBe(2);
    expect((float) $customerFromDb->transactions_sum_total_belanja)->toBe(200000.0);
});
