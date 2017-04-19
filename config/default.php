<?php
return [
    'Cors-default' => [
        'AllowOrigin' => true,
        'AllowCredentials' => true,
        'AllowMethods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],
        'AllowHeaders' => true,
        'ExposeHeaders' => false,
        'MaxAge' => 86400, // 1 day
        'exceptionRenderer' => 'Cors\Error\AppExceptionRenderer',
    ]
];
