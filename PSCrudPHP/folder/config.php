<?php

    return [
        'driver' => 'mysql',
        'host' => getenv('DB_HOST') ?: 'localhost',
        'database' => getenv('DB_DATABASE') ?: 'psPHPPDO',
        'port' => getenv('DB_PORT') ?: '3306',
        'user' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: ''
    ];