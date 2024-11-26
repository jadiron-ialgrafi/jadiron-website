<?php
date_default_timezone_set('Asia/Riyadh'); // UTC+3

return [
    'database' => [
        'connection' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'jadirons_web',
        'username' => 'jadirons_webuser',
        'password' => 'Eb@1122335566',
    ],
    'wp_database' => [ 
        'connection' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'jadirons_wp_umqjj',
        'username' => 'jadirons_webuser',
        'password' => 'Eb@1122335566',
    ],
    'mail' => [
        'mailer' => 'smtp',
        'host' => 'smtp.gmail.com',
        'port' => '587',
        'username' => 'info@jadiron.com',
        'password' => 'piucxlcbthtwkieo',
        'encryption' => 'tls',
        'from_address' => 'info@jadiron.com',
        'from_name' => 'Jadiron',
    ],
];
