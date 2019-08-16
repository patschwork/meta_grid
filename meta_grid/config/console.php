<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$db_alt = str_replace('sqlite:../../../../dwh_meta.sqlite', 'sqlite:../../../dwh_meta.sqlite', $db);

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
		// Diesen gii Config Eintrag verwenden, damit die korrekte Klasse für app\myTemplates\crud\Generator verwendet wird (statt der Default: yii\gii\generators\crud\Generator)
		'gii' => [
				'class' => 'yii\gii\Module',      
				//'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.178.20'],  
				'generators' => [ //here
					'crud' => [ // generator name
						// 'class' => 'yii\gii\generators\crud\Generator', // generator class
						'class' => 'app\myTemplates\crud\Generator', // generator class
						'templates' => [ //setting for out templates
							'myCrud' => '@app/myTemplates/crud/default', // template name => path to template
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
