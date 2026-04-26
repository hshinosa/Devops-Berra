<?php

use App\Models\Admin;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('admins can authenticate using the login screen', function () {
    $admin = Admin::factory()->create();

    $response = $this->post('/login', [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('super admins are redirected to users index after login', function () {
    $admin = Admin::factory()->superAdmin()->create();

    $response = $this->post('/login', [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('users.index', absolute: false));
});

test('admins can not authenticate with invalid password', function () {
    $admin = Admin::factory()->create();

    $this->post('/login', [
        'email' => $admin->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('admins can logout', function () {
    $admin = Admin::factory()->create();

    $response = $this->actingAs($admin)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
