<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\SystemInfoController;
use Illuminate\Http\Request;
use Illuminate\View\View;

test('system info controller returns intranet information view', function () {
    $user = new class {
        public string $name = 'Admin';
        public string $email = 'admin@example.com';
        public UserRole $role;

        public function __construct()
        {
            $this->role = UserRole::ADMIN;
        }
    };

    $request = Request::create('/admin/informasi-sistem', 'GET');
    $request->setUserResolver(fn () => $user);

    $controller = new SystemInfoController();
    $response = $controller->index($request);

    expect($response)->toBeInstanceOf(View::class);
    expect($response->getData()['systemInfo']['mode'])->toBe('INTRANET');
    expect($response->getData()['systemInfo']['network'])->toBe('LOCAL');
});
