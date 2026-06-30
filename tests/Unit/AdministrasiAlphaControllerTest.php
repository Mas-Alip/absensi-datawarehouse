<?php

use App\Enums\UserRole;
use App\Http\Controllers\Pegawai\AdministrasiAlphaController;
use Illuminate\Http\Request;
use Illuminate\View\View;

test('controller returns employee administration view', function () {
    $user = new class {
        public string $name = 'Budi Pegawai';
        public string $email = 'budi@example.com';
        public UserRole $role;

        public function __construct()
        {
            $this->role = UserRole::PEGAWAI;
        }
    };

    $request = Request::create('/pegawai/administrasi-alpha', 'GET');
    $request->setUserResolver(fn () => $user);

    $controller = new AdministrasiAlphaController();
    $response = $controller->index($request);

    expect($response)->toBeInstanceOf(View::class);
    expect($response->getData()['user'])->toBe($user);
});
