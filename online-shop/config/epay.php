<?php

return [


    'client_id'     => env('EPAY_CLIENT_ID'),
    'client_secret' => env('EPAY_CLIENT_SECRET'),
    'terminal_id'   => env('EPAY_TERMINAL_ID'),

    'oauth_url' => env('EPAY_OAUTH_URL'),

    'payform_js_url' => env('EPAY_PAYFORM_JS_URL'),

    'scope' => 'webapi usermanagement email_send verification statement statistics payment',

    'secret_salt' => env('EPAY_SECRET_SALT'),

    'back_link'          => env('EPAY_BACK_LINK'),
    'failure_back_link'  => env('EPAY_FAILURE_BACK_LINK'),
    'post_link'          => env('EPAY_POST_LINK'),
    'failure_post_link'  => env('EPAY_FAILURE_POST_LINK'),
];
