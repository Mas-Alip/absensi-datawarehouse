<?php

use App\Enums\UserRole;
use App\Models\User;

test('employee can view alpha administration page', function () {
    $user = new User();
    $user->forceFill([
        'name' => 'Budi Pegawai',
        'email' => 'budi@example.com',
        'role' => UserRole::PEGAWAI,
    ]);

    $response = $this->actingAs($user)->get(route('pegawai.administrasi-alpha'));

    $response->assertOk();
    $response->assertSee('Administrasi Alpha');
    $response->assertSee('Download PDF');
});
