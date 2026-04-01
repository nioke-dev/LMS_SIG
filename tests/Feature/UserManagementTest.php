<?php

use App\Models\User;

test('learning administrator can create a user with a valid role', function () {
    $admin = User::factory()->create([
        'role' => User::ROLE_LEARNING_ADMINISTRATOR,
    ]);

    $response = $this->actingAs($admin)->post(route('users.store'), [
        'name' => 'Coordinator Baru',
        'email' => 'coordinator-baru@example.com',
        'password' => 'password123',
        'role' => User::ROLE_LEARNING_COORDINATOR,
    ]);

    $response->assertRedirect(route('users.index'));

    $this->assertDatabaseHas('users', [
        'name' => 'Coordinator Baru',
        'email' => 'coordinator-baru@example.com',
        'role' => User::ROLE_LEARNING_COORDINATOR,
    ]);
});
