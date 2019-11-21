<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_ASSOC,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [
		
		// ### PRODUCTION 
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'dbmysql.tap-agri.com'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'helpdesk'),
            'username' => env('DB_USERNAME', 'admin'),
            'password' => env('DB_PASSWORD', 'p@ssw0rd'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],
		
		// ### DEVELOPMENT
		'xmysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '10.20.1.180'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'helpdesk'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', 'tap123'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],
		
        'oracle' => [
            'driver' => 'oracle',
            'host' => '10.20.1.248',
            'port' => '1521',
            'database' => 'staging',
            'username' => 'staging',
            'password' => 'staging',
            'charset' => 'AL32UTF8',
            'prefix' => '',
        ],
		
        'app_db' => [
            'driver'        => 'oracle',
            //'host'          => env('DB_HOST', '10.20.1.108'),
			//'host'          => env('DB_HOST', '10.20.1.109'),
			'host'          => env('DB_HOST', '10.20.1.111'),
            'port'          => env('DB_PORT', '1521'),
            'database'      => env('DB_DATABASE', 'tapapps'),
            'servicename'   => env('DB_DATABASE', 'tapapps'),
            'username'      => env('DB_USERNAME', 'helpdesk'),
            'password'      => env('DB_PASSWORD', 'helpdesk'),
            'prefix'        => env('DB_PREFIX', ''),
        ],
		
		
		'workshop' => [
            'driver' => 'oracle',
            'host' => '10.20.1.111',
            'port' => '1521',
            'database' => 'tapapps',
            'username' => 'ws_workflow',
            'password' => 'ws_workflow',
            'charset' => 'AL32UTF8',
            'prefix' => '',
        ],
		
		'irs' => [
            'driver' => 'oracle',
            'host' => '10.20.1.57',
            'port' => '1521',
            'database' => 'tapdw',
            'username' => 'qa_user',
            'password' => 'qa_user',
            'charset' => 'AL32UTF8',
            'prefix' => '',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => false,

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', ''),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
