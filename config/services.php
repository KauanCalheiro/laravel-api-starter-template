<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'api' => [
        'univates_api' => [
            'base_url' => env('UNIVATES_API_BASE_URL'),
            'api_key'  => env('UNIVATES_API_KEY'),
            'system'   => env('APP_NAME'),
        ],
        'mail_api' => [
            'base_url'     => env('MAIL_API_BASE_URL'),
            'system'       => env('APP_NAME'),
            'dev_name'     => env('MAIL_API_DEV_NAME', ''),
            'dev_from'     => env('MAIL_API_DEV_FROM', ''),
            'dev_to'       => env('MAIL_API_DEV_TO', ''),
            'dev_cc'       => env('MAIL_API_DEV_CC', ''),
            'dev_bcc'      => env('MAIL_API_DEV_BCC', ''),
            'dev_reply_to' => env('MAIL_API_DEV_REPLY_TO'),
            'dev_send_now' => env('MAIL_API_DEV_SEND_NOW', ''),
            'api_key'      => env('MAIL_API_KEY', ''),
        ],
    ],

    'soap' => [
        'alfa' => [
            'location'   => env('SOAP_ALFA_LOCATION'),
            'uri'        => env('SOAP_ALFA_URI'),
            'trace'      => env('SOAP_ALFA_TRACE'),
            'exceptions' => env('SOAP_ALFA_EXCEPTIONS'),
            'encoding'   => env('SOAP_ALFA_ENCODING'),
            'key'        => env('SOAP_ALFA_KEY'),
        ],
        'ecm' => [
            'location'   => env('SOAP_ECM_LOCATION'),
            'uri'        => env('SOAP_ECM_URI'),
            'trace'      => env('SOAP_ECM_TRACE'),
            'exceptions' => env('SOAP_ECM_EXCEPTIONS'),
            'encoding'   => env('SOAP_ECM_ENCODING'),
            'key'        => env('SOAP_ECM_KEY'),
        ],
    ],
];
