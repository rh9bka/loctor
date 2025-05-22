<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'enableCsrfValidation' => false,
            'cookieValidationKey' => 'your-secret-key-here',
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/ads'],
                    'pluralize' => false,
                    'patterns' => [
                        'GET' => 'index',
                        'POST' => 'create',
                        'GET {id}' => 'view',
                        'PUT {id}' => 'update',
                        'DELETE {id}' => 'delete',
                    ],
                    'extraPatterns' => [
                        'GET my-ads' => 'my-ads',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/auth'],
                    'pluralize' => false,
                    'patterns' => [
                        'POST register' => 'register',
                        'POST verify' => 'verify',
                        'POST login' => 'login',
                    ],
                ],
                'v1/ads' => [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/ads',
                    'patterns' => [
                        'GET' => 'index',
                        'POST' => 'create',
                        'GET {id}' => 'view',
                        'PUT {id}' => 'update',
                        'DELETE {id}' => 'delete',
                    ],
                    'extraPatterns' => [
                        'GET my-ads' => 'my-ads',
                        'POST {id}/favorite' => 'add-to-favorites',
                        'DELETE {id}/favorite' => 'remove-from-favorites',
                        'GET favorites' => 'favorites',
                        'GET slug/{slug}' => 'view-by-slug',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/categories'],
                    'pluralize' => false,
                ],
                'v1/categories' => [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/categories',
                    'patterns' => [
                        'GET tree' => 'tree',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
]; 