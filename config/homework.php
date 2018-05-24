<?php

return [
    'recaptcha' => [
        'key' => env('RECAPTCHA_KEY'),
        'secret' => env('RECAPTCHA_SECRET')
    ],

    'administrators' => [
        env('ADMIN_EMAIL')
        // Add the email addresses of users who should be administrators here.
    ]
];
