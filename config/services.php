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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'stripe' => [
        'secret' => env('STRIPE_API'),
        'secret' => env('STRIPE_SECRET_KEY'),
    ],

    'facebook' => [
        'client_id' => env('APP_ID', '288376219461364'),
        'client_secret' => env('APP_SECRET', '04065f19c5606d51f23c223d1a13ba2c'),
        'redirect' => env('APP_REDIRECT_URL' , 'https://infuee.softuvo.xyz/auth/facebook/callback'),
    ],

    'instagram' => [  
        'client_id' => env('INSTAGRAM_CLIENT_ID'),  
        'client_secret' => env('INSTAGRAM_CLIENT_SECRET'),  
        'redirect' =>  env('INSTAGRAM_REDIRECT_URL' , 'https://infuee.softuvo.xyz/auth/instagram/callback'),
    ],

    'youtube' => [    
        'client_id' => env('YOUTUBE_CLIENT_ID'),  
        'client_secret' => env('YOUTUBE_CLIENT_SECRET'),  
        'redirect' => env('YOUTUBE_REDIRECT_URL' , 'https://infuee.softuvo.xyz/auth/youtube/callback'), 
    ],

    'twitter' => [
        'client_id' => env('TWITTER_API_KEY', '7dV63mAchF7XMZncuZZP8nld5'),
        'client_secret' => env('TWITTER_API_SECRET_KEY', '5E68r8EMMKZIDbNmWBKc4ITebbx1jaPqgeWIkGy9leAD0jcBiS'),
        'redirect' => env('TWITTER_REDIRECT_URL' , 'https://infuee.softuvo.xyz/auth/twitter/callback'),
    ],

];
