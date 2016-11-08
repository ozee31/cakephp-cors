<?php
use Cake\Event\EventManager;
use Cake\Core\Configure;
use Cors\Routing\Middleware\CorsMiddleware;

/**
 * Configuration
 */
Configure::load('Cors.default', 'default');

$defaultConfig = (array) Configure::consume('Cors-default');
$personnalConfig = (array) Configure::consume('Cors');

Configure::write('Cors', array_merge($defaultConfig, $personnalConfig));

debug(Configure::read('Cors'));

/**
 * Middleware
 */
EventManager::instance()->on('Server.buildMiddleware',
    function ($event, $middleware) {
        $middleware->add(new CorsMiddleware());
    }
);
