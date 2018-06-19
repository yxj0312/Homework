<?php

return [
    'recaptcha' => [
        'key' => env('RECAPTCHA_KEY'),
        'secret' => env('RECAPTCHA_SECRET')
    ],
    'administrators' => [
        env('ADMIN_EMAIL')
        // Add the email addresses of users who should be administrators here.
    ],
    'reputation' => [
        'thread_published' => 10,
        'reply_posted' => 2,
        'best_reply_awarded' => 50,
        'reply_favorited' => 5
    ]
];
