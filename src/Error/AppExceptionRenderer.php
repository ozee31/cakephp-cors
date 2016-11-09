<?php
namespace Cors\Error;

use Cors\Controller\ErrorController;
use Cake\Error\ExceptionRenderer;
use Cake\Routing\Router;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\Event\Event;
use Exception;

class AppExceptionRenderer extends ExceptionRenderer {

    protected function _getController() {
        if (!$request = Router::getRequest(true)) {
            $request = Request::createFromGlobals();
        }
        $response = new Response();

        try {
            $controller = new ErrorController($request, $response);
            $controller->startupProcess();
            $startup = true;
        } catch (Exception $e) {
            $startup = false;
        }

        // Retry RequestHandler, as another aspect of startupProcess()
        // could have failed. Ignore any exceptions out of startup, as
        // there could be userland input data parsers.
        if ($startup === false && !empty($controller) && isset($controller->RequestHandler)) {
            try {
                $event = new Event('Controller.startup', $controller);
                $controller->RequestHandler->startup($event);
            } catch (Exception $e) {
            }
        }
        if (empty($controller)) {
            $controller = new Controller($request, $response);
        }

        return $controller;
    }
}
