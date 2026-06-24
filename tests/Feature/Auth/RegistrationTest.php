<?php

test('registration screen redirects to login', function () {
    $response = $this->get('/register');

    $response->assertRedirect(route('login'));
});

test('guest cannot submit registration', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(419);
    $this->assertGuest();
});
