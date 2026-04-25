<?php

use App\Models\Admin;

/*
|--------------------------------------------------------------------------
| Guest Access Tests
|--------------------------------------------------------------------------
*/

test('guest cannot access dashboard', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});

test('guest cannot access user management', function () {
    $response = $this->get('/users');

    $response->assertRedirect('/login');
});

test('guest cannot access products', function () {
    $response = $this->get('/products');

    $response->assertRedirect('/login');
});

test('guest cannot access profile', function () {
    $response = $this->get('/profile');

    $response->assertRedirect('/login');
});

/*
|--------------------------------------------------------------------------
| Regular Admin Access Tests
|--------------------------------------------------------------------------
*/

test('regular admin can access dashboard', function () {
    $admin = Admin::factory()->create();

    $response = $this->actingAs($admin)->get('/dashboard');

    $response->assertStatus(200);
});

test('regular admin cannot access user management', function () {
    $admin = Admin::factory()->create();

    $response = $this->actingAs($admin)->get('/users');

    $response->assertStatus(403);
});

test('regular admin cannot create users', function () {
    $admin = Admin::factory()->create();

    $response = $this->actingAs($admin)->get('/users/create');

    $response->assertStatus(403);
});

test('regular admin can access products', function () {
    $admin = Admin::factory()->create();

    $response = $this->actingAs($admin)->get('/products');

    $response->assertStatus(200);
});

test('regular admin can access profile', function () {
    $admin = Admin::factory()->create();

    $response = $this->actingAs($admin)->get('/profile');

    $response->assertStatus(200);
});

test('regular admin is redirected to dashboard after login', function () {
    $admin = Admin::factory()->create();

    $response = $this->post('/login', [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

/*
|--------------------------------------------------------------------------
| Super Admin Access Tests
|--------------------------------------------------------------------------
*/

test('super admin can access dashboard', function () {
    $admin = Admin::factory()->superAdmin()->create();

    $response = $this->actingAs($admin)->get('/dashboard');

    $response->assertStatus(200);
});

test('super admin can access user management', function () {
    $admin = Admin::factory()->superAdmin()->create();

    $response = $this->actingAs($admin)->get('/users');

    $response->assertStatus(200);
});

test('super admin can access products', function () {
    $admin = Admin::factory()->superAdmin()->create();

    $response = $this->actingAs($admin)->get('/products');

    $response->assertStatus(200);
});

test('super admin is redirected to users index after login', function () {
    $admin = Admin::factory()->superAdmin()->create();

    $response = $this->post('/login', [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('users.index', absolute: false));
});

/*
|--------------------------------------------------------------------------
| Login Validation Tests
|--------------------------------------------------------------------------
*/

test('login fails with empty credentials', function () {
    $response = $this->post('/login', [
        'email' => '',
        'password' => '',
    ]);

    $response->assertSessionHasErrors(['email', 'password']);
});

test('login fails with invalid email format', function () {
    $response = $this->post('/login', [
        'email' => 'not-an-email',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('login fails with non-existent email', function () {
    $response = $this->post('/login', [
        'email' => 'nonexistent@example.com',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors(['email']);
});

/*
|--------------------------------------------------------------------------
| Registration Tests
|--------------------------------------------------------------------------
*/

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new admin can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test Admin',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('registration fails with duplicate email', function () {
    Admin::factory()->create(['email' => 'taken@example.com']);

    $response = $this->post('/register', [
        'name' => 'Another Admin',
        'email' => 'taken@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors(['email']);
});

test('registration fails with mismatched passwords', function () {
    $response = $this->post('/register', [
        'name' => 'Test Admin',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'different-password',
    ]);

    $response->assertSessionHasErrors(['password']);
});
