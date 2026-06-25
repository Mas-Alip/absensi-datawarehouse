<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Status Pembatasan Jaringan Kantor
    |--------------------------------------------------------------------------
    |
    | Jika diatur ke true, presensi dengan status HADIR hanya bisa dilakukan 
    | di dalam jaringan IP kantor yang terdaftar di bawah ini.
    |
    */
    'enabled' => env('OFFICE_NETWORK_RESTRICTION', true),

    /*
    |--------------------------------------------------------------------------
    | Daftar IP / Subnet Kantor yang Diizinkan
    |--------------------------------------------------------------------------
    |
    | Nilai ini akan membaca variabel 'OFFICE_IP_RANGES' dari Railway.
    | Kamu bisa memasukkan beberapa IP sekaligus dengan memisahkannya menggunakan koma (,).
    | Jika variabel di Railway kosong, sistem otomatis menggunakan default IP lokal.
    |
    */
    'office_ip_ranges' => explode(',', env('OFFICE_IP_RANGES', '127.0.0.1,192.168.0.0/24,192.168.1.0/24,10.0.0.1,36.68.53.204')),
];
