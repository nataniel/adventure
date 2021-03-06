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
];