<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Supabase
    |--------------------------------------------------------------------------
    |
    | Nilai-nilai ini dibaca dari file .env. Service role key bersifat RAHASIA
    | dan hanya digunakan di sisi server (jangan pernah diekspos ke browser).
    |
    */

    'url' => env('SUPABASE_URL'),

    'anon_key' => env('SUPABASE_ANON_KEY'),

    'service_role_key' => env('SUPABASE_SERVICE_ROLE_KEY'),

    'bucket' => env('SUPABASE_BUCKET', 'k3-files'),

];
