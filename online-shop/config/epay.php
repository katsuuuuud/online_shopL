<?php

return [


    'client_id'     => env('EPAY_CLIENT_ID', 'test'),
    'client_secret' => env('EPAY_CLIENT_SECRET', 'yF587AV9Ms94qN2QShFzVR3vFnWkhjbAK3sG'),
    'terminal_id'   => env('EPAY_TERMINAL_ID', '67e34d63-102f-4bd1-898e-370781d0074d'),

    'oauth_url' => env('EPAY_OAUTH_URL', 'https://test-epay-oauth.epayment.kz/oauth2/token'),

    'payform_js_url' => env('EPAY_PAYFORM_JS_URL', 'https://test-epay.epayment.kz/payform/payment-api.js'),

    'scope' => 'webapi usermanagement email_send verification statement statistics payment',

    'secret_salt' => env('EPAY_SECRET_SALT', 'change-me-in-env'),

    'back_link'          => env('EPAY_BACK_LINK', env('APP_URL') . '/payment/success'),
    'failure_back_link'  => env('EPAY_FAILURE_BACK_LINK', env('APP_URL') . '/payment/failure'),
    'post_link'          => env('EPAY_POST_LINK', env('APP_URL') . '/api/epay/postlink'),
    'failure_post_link'  => env('EPAY_FAILURE_POST_LINK', env('APP_URL') . '/api/epay/postlink/failure'),
];
