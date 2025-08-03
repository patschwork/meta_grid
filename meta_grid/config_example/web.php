<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'id' => 'basic',
    'name' => 'meta#grid',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'translatemanager','app\components\LanguageSelector' ],	// 2016-03-21 // 2016-04-01
    'components' => [
   		'translatemanager' => [
			'class' => 'lajax\translatemanager\Component'
   		],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'xxx',
        ],
        'cache' => [
            // 'class' => 'yii\caching\FileCache',
            'class' => 'yii\caching\ApcCache',
            'useApcu' => true
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'encryption' => 'ssl',
                'host' => 'smtp.xyz.com',
                'port' => '465',
                'username' => 'MAIL_USER_ACCOUNT',
                'password' => 'MAIL_USER_PASSWORD',
            ],             
        ],
        // 'log' => [
        //     'traceLevel' => YII_DEBUG ? 3 : 0,
        //     'targets' => [
        //         [
        //             'class' => 'yii\log\FileTarget',
        //             'levels' => ['error', 'warning'],
        //         ],
        //     ],
        // ],
        'i18n' => [
            'translations' => [
                '*' => 
                [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'xx-XX', // Developer language
                    'sourceMessageTable' => '{{%language_source}}',
                    'messageTable' => '{{%language_translate}}',
                    'cachingDuration' => 86400,
                    'forceTranslation' => true,
                    'enableCaching' => false,
                ],
                'app' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'xx-XX', // Developer language
                    // 'sourceLanguage' => 'en-US', // Developer language
                    'sourceMessageTable' => '{{%language_source}}',
                    'messageTable' => '{{%language_translate}}',
                    'cachingDuration' => 86400,
                    'forceTranslation' => true,
                    'enableCaching' => false,
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
		'authManager' => [
			'class' => 'Da\User\Component\AuthDbManagerComponent',
		],
    ],
    'params' => $params,
	'modules' => 
        [
			'redactor' => 'yii\redactor\RedactorModule',
			'translatemanager' => 
                [
					'class' => 'lajax\translatemanager\Module',
					'roles' => ['mgadmin','admin'],               // For setting access levels to the translating interface.
			    ],
			'user' => 
                [
					'class' => Da\User\Module::class,
					'classMap' => 
                        [
	    					'User' => 'vendor\meta_grid\user_model\User',
    					],
                    'administrators' => ['admin'],
                    'administratorPermissionName' => 'mgadmin',
                    'mailParams' => 
                        [
                            'fromEmail' => $params['adminEmail'],
                        ],
				],
		],
];
 
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',      
        //'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.178.20'],  
        'generators' => [ //here
            'crud' => [ // generator name
                'class' => 'vendor\meta_grid\gii_template\crud\Generator', // generator class
                'templates' => [ //setting for out templates
                    'myCrud' => '@vendor/meta_grid/gii_template/crud/default', // template name => path to template
                ]
            ]
        ],
    ];
}

return $config;
