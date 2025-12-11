<?php

return [
    'class' => 'yii\db\Connection',
    // Для Yii
    'dsn' => 'mysql:host=taskforce_db;dbname=taskforce',
    // Для Фейкера и фикстур
    // 'dsn' => 'mysql:host=127.0.0.1;port=3380;dbname=taskforce', 
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
