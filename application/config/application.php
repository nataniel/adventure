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
        'default_graph_version' => 'v4.0',
    ],
];