<?php

use App\Models\Admin;
use App\Models\AdminActivityLog;
use Inertia\Testing\AssertableInertia;

test('dashboard page can be accessed by authenticated admin', function () {
    $admin = Admin::factory()->superAdmin()->create();

    $this->actingAs($admin)
        ->get('/dashboard')
        ->assertStatus(200);
});

test('dashboard page displays statistics', function () {
    $admin = Admin::factory()->superAdmin()->create();

    $this->actingAs($admin)
        ->get('/dashboard')
        ->assertStatus(200)
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Index')
            ->has('stats')
        );
});

test('activity log can be filtered', function () {
    $admin = Admin::factory()->superAdmin()->create();

    AdminActivityLog::create([
        'admin_id' => $admin->id,
        'module' => 'users',
        'action' => 'created',
        'description' => 'Created user X',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test Agent',
    ]);

    $this->actingAs($admin)
        ->get('/dashboard?action=created')
        ->assertStatus(200)
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Dashboard/Index')
            ->has('activities')
            ->where('filters.action', 'created')
        );
});
