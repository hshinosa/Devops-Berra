<?php

use App\Models\Admin;

beforeEach(function () {
    $this->admin = Admin::factory()->create();
    $this->withoutVite();
});

test('admin dapat melihat halaman edit profil', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('profile.edit'));

    $response->assertStatus(200);
});

test('admin dapat mengupdate nama profil', function () {
    $response = $this->actingAs($this->admin)
        ->patch(route('profile.update'), [
            'name' => 'Nama Baru',
            'email' => $this->admin->email,
        ]);

    $response->assertRedirect(route('profile.edit'));
    $this->admin->refresh();
    expect($this->admin->name)->toBe('Nama Baru');
});

test('admin dapat mengupdate email profil', function () {
    $response = $this->actingAs($this->admin)
        ->patch(route('profile.update'), [
            'name' => $this->admin->name,
            'email' => 'emailbaru@example.com',
        ]);

    $response->assertRedirect(route('profile.edit'));
    $this->admin->refresh();
    expect($this->admin->email)->toBe('emailbaru@example.com');
});

test('update profil gagal jika email tidak valid', function () {
    $response = $this->actingAs($this->admin)
        ->patch(route('profile.update'), [
            'name' => $this->admin->name,
            'email' => 'bukan-email',
        ]);

    $response->assertSessionHasErrors('email');
});

test('update profil gagal jika nama kosong', function () {
    $response = $this->actingAs($this->admin)
        ->patch(route('profile.update'), [
            'name' => '',
            'email' => $this->admin->email,
        ]);

    $response->assertSessionHasErrors('name');
});

test('update profil gagal jika email sudah dipakai admin lain', function () {
    $otherAdmin = Admin::factory()->create(['email' => 'taken@example.com']);

    $response = $this->actingAs($this->admin)
        ->patch(route('profile.update'), [
            'name' => $this->admin->name,
            'email' => 'taken@example.com',
        ]);

    $response->assertSessionHasErrors('email');
});
