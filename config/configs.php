<?php

return [
    'authentication' => [
        'allow_api_documentation_on_production' => true,
        'users' => [
            'min_random_code_user' => 100000,
            'max_random_code_user' => 990000,
            'length_code' => 6,
        ],
        'otp' => [
            'expired_time' => 75,/*todo time is seconds ??!! */
            'min_number_random' => 11111,
            'max_number_random' => 99999,
        ],
    ],
    'api_key' => [
        'length' => 20,
        'expired_time' => 80,/*minute */
    ],
    'images' => [
        'destination_path_default' => 'public/images',
        'mimes_allowable_from_upload' => [
            'jpeg',
            'JPG',
            'jpg',
            'png',
            'gif',
            'svg',
        ],
    ],
    'files' => [
        'destination_path_default' => 'files',
        'mimes_allowable_from_upload' => [
            'txt',
            'pdf',
            'excel',
            'xlx',
            'xls',
            'xlsx',
        ],
    ],
    'posts' => [
        'length_short_text' => 20,
        'scores' => [
            'min_score_limit' => 0,
            'max_score_limit' => 10
        ],
    ],
    'gyms' => [
        'cache_time_initialize_requests_selectors' => 20,
    ],
    'notifications' => [
        'notification' => [
            'min_priority_limit' => 0,
            'max_priority_limit' => 10
        ],
        'sms' => [
            'ghasedak' => [
                'api_key' => env('API_KEY_GHASEDAK')
            ],
        ],
    ],
    'payment' => [
        'factor' => [
            'min_random_code_factor' => 100000,
            'max_random_code_factor' => 990000,
        ],
        'zarinpal' => [
            'merchant_id' => env('PAYMENT_ZARINPAL_TOKEN','jfdlkajafjhio'),
            'payment_url' => 'https://api.zarinpal.com/pg/v4/payment/',
        ],
    ],
    'web_info' => [
        'mobile' => 'mobile',
        'address' => 'address',
        'fax' => 'fax',
        'description' => 'description',
        'telegram' => 'telegram',
        'instagram' => 'instagram',
        'email' => 'email',
    ],
];
