<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . env('MYSQL_HOST') . ';dbname=' . env('MYSQL_DATABASE'),
    'username' => env('MYSQL_USER'),
    'password' => env('MYSQL_PASSWORD'),
    'charset' => 'utf8',
];
