<?php
namespace Cors\Controller;

use App\Controller\ErrorController as BaseErrorController;
use Cake\Event\Event;

class ErrorController extends BaseErrorController {

    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize() {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    /**
     * beforeRender callback.
     *
     * @param \Cake\Event\Event $event Event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event) {
        parent::beforeRender($event);
        $this->response->header(['Access-Control-Allow-Origin' => '*']);
    }
}
