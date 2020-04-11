<?php
return [
    'database' => [
        'driver' => 'pdo_mysql',
        'dbname' => 'adventure',
        'user' => 'user',
        'password' => 'password',
        'host' => 'localhost',
        'charset' => 'utf8',
    ],

    /**
     * Base URL for non-http requests and all other cases when
     * the Base URL cannot be determined automatically from $_SERVER
     */
    'base_url' => 'http://someurl.com',
    'ssl_required' => false,

    /**
     * Doctrine configuration settings for Entity Manager
     * @link http://docs.doctrine-project.org/en/2.0.x/reference/configuration.html
     */
    'doctrine' => [
        'auto_generate_proxies' => false,
        'cache_class' => \Doctrine\Common\Cache\ApcuCache::class,
    ],

    'show_errors' => false,

    /**
     * https://developers.facebook.com/apps/536959080539510/dashboard/
     */
    'facebook' => [
        'app_id' => '536959080539510',
        'app_secret' => '**************',
        'default_graph_version' => 'v6.0',
    ],

    /**
     * http://steamcommunity.com/dev/apikey
     */
    'steam' => [
        'api_key' => '6F4E2F5112CDE286D5C83D359C781510',
    ],

    /**
     * https://console.developers.google.com/apis/credentials?project=adventure-273917
     */
    'google' => [
        'client_id' => '708272906850-ah71j2864oge9bfan3fve2nfih9hntea.apps.googleusercontent.com',
        'client_secret' => '**************',
    ],
];