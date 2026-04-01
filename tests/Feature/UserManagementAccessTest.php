<?php

use App\Models\User;

test('learning administrator can access user management index', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_LEARNING_ADMINISTRATOR,
    ]);

    $response = $this->actingAs($admin)->get(route('users.index'));

    $response->assertOk();
});

test('employee cannot access user management index', function () {
    $employee = User::factory()->create([
        'role' => User::ROLE_EMPLOYEE,
    ]);

    $response = $this->actingAs($employee)->get(route('users.index'));

    $response->assertForbidden();
});

test('guest is redirected to login when opening user management index', function () {
    $response = $this->get(route('users.index'));

    $response->assertRedirect(route('login'));
});
