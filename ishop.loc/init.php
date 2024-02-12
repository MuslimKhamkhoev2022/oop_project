<?php
session_start();

require_once "classes/Database.php";
require_once "classes/Config.php";
require_once "classes/Validate.php";
require_once "classes/Input.php";
require_once "classes/Token.php";
require_once "classes/Session.php";
require_once "classes/User.php";
require_once "classes/Redirect.php";

$GLOBALS['config'] = [
    'mysql' => [
        'host' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'database' => 'users',
        'something' => [
            'no' => [
                'foo' => [
                    'bar' => 'baz'
                ]
            ],
        ]
    ],
    'session' => [
        'token_name' => 'token',
        'user_session' => 'user'
    ]
];
//Redirect::to('test.php');
//Redirect::to(404);

