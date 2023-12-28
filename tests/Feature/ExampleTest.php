<?php

use App\Models\User;

it('redirects to login', function () {
    $this->get('/')
        ->assertRedirect('/login');
});

it('redirects to dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/')
        ->assertRedirect('/dashboard');
});
