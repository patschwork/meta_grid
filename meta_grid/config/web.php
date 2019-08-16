<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'translatemanager','app\components\LanguageSelector' ],	// 2016-03-21 // 2016-04-01
	// 'language' => 'de-DE',				// 2016-03-01
	// 'language' => 'de-MS_SSRS',				// 2016-03-01
    'components' => [
   		'translatemanager' => [
			'class' => 'lajax\translatemanager\Component'
   		],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'xxx',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        // 'user' => [
            // // 'identityClass' => 'app\models\User',
            // 'identityClass' => 'vendor\meta_grid\user_model\User',
            // // 'enableAutoLogin' => true,
        // ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        // 'log' => [
            // 'traceLevel' => YII_DEBUG ? 3 : 0,
            // 'targets' => [
                // [
                    // 'class' => 'yii\log\FileTarget',
                    // 'levels' => ['error', 'warning'],
                // ],
            // ],
        // ],
		'raven' => [
				'class' => 'sheershoff\sentry\ErrorHandler',
				//'dsn' => 'http://e35918b84af8411dbe1e0a02d8ca0760:f9321e3f1dd54b69a05c673e05a500ab@vps249413.ovh.net:9000/2', // Sentry DSN
			],
			'log' => [
				'targets' => [
					[
						'class' => 'sheershoff\sentry\Target',
						'levels' => ['error', 'warning'],
						//'dsn' => 'http://e35918b84af8411dbe1e0a02d8ca0760:f9321e3f1dd54b69a05c673e05a500ab@vps249413.ovh.net:9000/2', // Sentry DSN
					]
				],
			],		
    		'i18n' => [
    				'translations' => [
    						'*' => [
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
    		'piwik' => [
    				'class' => 'sitawit\piwik\Piwik',
//     				'trackerUrl' => 'vps249413.ovh.net/piwik',
    				'siteId' => '2'
    		],
        'db' => require(__DIR__ . '/db.php'),
		'authManager' => [
			'class' => 'Da\User\Component\AuthDbManagerComponent',
			// 'class' => 'yii\rbac\DbManager',
		],
    ],
    'params' => $params,
	'modules' => [
			'redactor' => 'yii\redactor\RedactorModule',
			'translatemanager' => [
					'class' => 'lajax\translatemanager\Module',
					'roles' => ['mgadmin','admin'],               // For setting access levels to the translating interface.
			],
			// 'translatemanager' => [
            // 'class' => 'lajax\translatemanager\Module',
            // 'root' => '@app',               // The root directory of the project scan.
            // 'layout' => 'language',         // Name of the used layout. If using own layout use 'null'.
            // 'allowedIPs' => ['*'],          // IP addresses from which the translation interface is accessible.
            // 'tmpDir' => '@app/runtime',         // Writable directory for the client-side temporary language files. 
                                            // // IMPORTANT: must be identical for all applications (the AssetsManager serves the JavaScript files containing language elements from this directory).
            // 'phpTranslators' => ['::t'],    // list of the php function for translating messages.
            // 'jsTranslators' => ['lajax.t'], // list of the js function for translating messages.
            // 'patterns' => ['*.js', '*.php'],// list of file extensions that contain language elements.
            // 'ignoredCategories' => ['yii'], // these categories won’t be included in the language database.
            // 'ignoredItems' => ['config'],   // these files will not be processed.
            // 'scanTimeLimit' => null,        // increase to prevent "Maximum execution time" errors, if null the default max_execution_time will be used
            // 'searchEmptyCommand' => '!',    // the search string to enter in the 'Translation' search field to find not yet translated items, set to null to disable this feature
            // 'defaultExportStatus' => 1,     // the default selection of languages to export, set to 0 to select all languages by default
            // 'defaultExportFormat' => 'json',// the default format for export, can be 'json' or 'xml'
            // 'tables' => [                   // Properties of individual tables
                // [
                    // 'connection' => 'db',   // connection identifier
                    // 'table' => '{{%languageX}}',         // table name
                    // 'columns' => ['name', 'name_ascii'] //names of multilingual fields
                // ],
            // ]
        // ],
			'user' => [
					'class' => Da\User\Module::class,
					'classMap' => [
						'User' => 'vendor\meta_grid\user_model\User',
						// 'identityClass' => 'vendor\meta_grid\user_model\User',
					],
                    'administrators' => ['admin'],
                    'administratorPermissionName' => 'mgadmin',
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
                // 'class' => 'yii\gii\generators\crud\Generator', // generator class
                'class' => 'app\myTemplates\crud\Generator', // generator class
                'templates' => [ //setting for out templates
                    'myCrud' => '@app/myTemplates/crud/default', // template name => path to template
                ]
            ]
        ],
    ];
}

return $config;
