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
    'base_url' => 'http://adventure.rebel.pl/',
    'ssl_required' => false,

    /**
     * Doctrine configuration settings for Entity Manager
     * @link http://docs.doctrine-project.org/en/2.0.x/reference/configuration.html
     */
    'doctrine' => [
        'auto_generate_proxies' => false,
        'cache_class' => \Doctrine\Common\Cache\ApcuCache::class,
        'cache_namespace' => 'adventure',
    ],

    'show_errors' => false,
];