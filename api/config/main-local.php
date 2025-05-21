<?php

return [
    'components' => [
        'request' => [
            'cookieValidationKey' => 'dGhpc2lzYXZlcnlsb25nc2VjcmV0a2V5Zm9yY29va2lldmFsaWRhdGlvbjEyMzQ1Njc4OTBhYmNkZWY=',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=loctor',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],
    ],
]; 