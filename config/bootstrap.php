<?php
use Cake\Event\EventManager;
use Cake\Core\Configure;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cors\Routing\Middleware\CorsMiddleware;

/**
 * Configuration
 */
Configure::load('Cors.default', 'default');

$defaultConfig = (array) Configure::consume('Cors-default');
$personnalConfig = (array) Configure::consume('Cors');
$config = array_merge($defaultConfig, $personnalConfig);

Configure::write('Cors', $config);

if ($config['exceptionRenderer'] && Configure::read('Error.exceptionRenderer') != $config['exceptionRenderer']) {
    Configure::write('Error.baseExceptionRenderer', Configure::read('Error.exceptionRenderer'));
    Configure::write('Error.exceptionRenderer', $config['exceptionRenderer']);
}

/**
 * Middleware
 */
EventManager::instance()->on('Server.buildMiddleware',
    function ($event, $middleware) {
        try {
            $middleware->insertBefore(RoutingMiddleware::class, new CorsMiddleware());
        } catch (\LogicException $exception) {
            $middleware->add(new CorsMiddleware());
        }
    }
);
