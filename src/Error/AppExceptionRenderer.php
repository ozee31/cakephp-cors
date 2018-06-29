<?php
namespace Cors\Error;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\Routing\Router;
use Exception;
use Cors\Routing\Middleware\CorsMiddleware;

function get_dynamic_parent() {
    return Configure::read('Error.baseExceptionRenderer');// return what you need
}
class_alias(get_dynamic_parent(), 'Cors\Error\BaseExceptionRenderer');

class AppExceptionRenderer extends BaseExceptionRenderer
{

    /**
     * Returns the current controller.
     *
     * @return \Cake\Controller\Controller
     */
    protected function _getController()
    {
        $controller = parent::_getController();
        $cors = new CorsMiddleware();
        $controller->response = $cors(
            $controller->request, $controller->response,
            function($request, $response){ return $response; }
        );
        return $controller;
    }
}
