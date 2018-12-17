<?php
return [
    'onlyDefault'=>env('SEND_ONLY_TO_DEFAULT_ADDESSES','false'),
    'defaultTo' => [
        [
            'address' => env('MAIL_TO_ADDRESS', 'hello@example.com'),
            'name' => env('MAIL_TO_NAME', 'Example'),
        ]
    ],
];