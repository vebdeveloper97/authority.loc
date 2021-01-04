<?php

use yii\db\Connection;

return [
    'master' => [
        'class'    => Connection::class,
        'dsn'      => 'pgsql:host=postgres;port=5432;dbname=db_app',
        'username' => 'user',
        'password' => 'password',
        'charset'  => 'utf8',
    ],
];
