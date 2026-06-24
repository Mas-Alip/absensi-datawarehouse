<?php

return [
    'enabled' => env('OFFICE_NETWORK_RESTRICTION', true),

    'office_ip_ranges' => [
        '192.168.0.0/24',
        '192.168.1.0/24',
        '10.0.0.0/24',
    ],
];
