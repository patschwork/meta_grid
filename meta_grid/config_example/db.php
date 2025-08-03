<?php

return [
    'class' => 'yii\db\Connection',

    // Postgres
    'dsn' => 'pgsql:host=localhost;dbname=metagrid_lqb_env',	
    'username' => 'metagrid_usr',
    'password' => '__SECRET_PASSWORD__',
    'charset' => 'utf8',

    // SQLite
    // 'dsn' => 'sqlite:../../../../dwh_meta.sqlite',	
    // 'on afterOpen' => function($event) {
    //    $event->sender->createCommand("PRAGMA foreign_keys = ON")->execute();
    //  },
    
    // Cache
    // 'enableSchemaCache' => true,
    // 'schemaCacheDuration' => 3600,
    // 'schemaCache' => 'cache',
    // 'enableQueryCache'=>true,
    // 'queryCacheDuration'=>3600,
];
