<?php
return [
    'default_locale' => 'pl',
    'show_errors' => true,
    'session_name' => 'AdventureSession',

    'authentication' => [
        // implementation of E4u\Authentication\Identity interface
        'model' => Main\Model\User::class,
        'login' => '/security',
        'cookie_name' => 'AdventureAuthentication',
    ],

    /**
     * https://developers.facebook.com/apps/135189156611879/dashboard/?business_id=281132869070424
     */
    'facebook' => [
        'app_id' => '536959080539510',
        'app_secret' => '9e094cca94677bdcc677623b39e86af2',
        'default_graph_version' => 'v6.0',
    ],

    /**
     * http://steamcommunity.com/dev/apikey
     */
    'steam' => [
        'api_key' => '6F4E2F5112CDE286D5C83D359C781510',
    ],

    /**
     * https://console.developers.google.com/apis/credentials?project=silicon-cocoa-255607
     */
    'google' => [
        'client_id' => '708272906850-ah71j2864oge9bfan3fve2nfih9hntea.apps.googleusercontent.com',
        'client_secret' => 'L160OtK1axHPEUiKFmtAak_F',
    ],
];