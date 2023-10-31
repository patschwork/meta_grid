<?php

return [
    'class' => 'yii\db\Connection',
#    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
     'dsn' => 'sqlite:../../../../dwh_meta.sqlite',	
#    'username' => 'root',
#    'password' => '',
    'charset' => 'utf8',
    'on afterOpen' => function($event) {
       $event->sender->createCommand("PRAGMA foreign_keys = ON")->execute();
        },
        
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',

    'enableQueryCache'=>true,
    'queryCacheDuration'=>3600,
];
