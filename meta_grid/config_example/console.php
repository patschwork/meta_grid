<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

// the path to the sqlite3 database file is from the console perspective shorter and must be modified to be reused here
$db_alt = $db;
$db_alt['dsn'] = str_replace('sqlite:../../../../dwh_meta.sqlite', 'sqlite:../../../dwh_meta.sqlite', $db_alt['dsn']);

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
		'gii' => [
				'class' => 'yii\gii\Module',      
				'generators' => [
					'crud' => [
						'class' => 'vendor\meta_grid\gii_template\crud\Generator',
						'templates' => [ 
							'myCrud' => '@vendor/meta_grid/gii_template/crud/default',
						]
					]
				],
			],	
		'user' => [
			'class' => Da\User\Module::class,
		]
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db_alt,
		'authManager' => [
			'class' => 'Da\User\Component\AuthDbManagerComponent',
		],
    ],
    'params' => $params,
];
