<?php
namespace Cors\TestCase\Controller;

use PHPUnit\Framework\TestCase;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\Event\Event;

class ErrorControllerTest extends TestCase
{
    private $controller;

    public function testIncomplete() {
        $this->markTestIncomplete('Fail with travis because ErrorController extends App\Controller\ErrorController');
    }

    // public function setUp()
    // {
    //     parent::setUp();
    //     $request = new Request();
    //     $response = new Response();
    //     $this->controller = $this->getMockBuilder('Cors\Controller\ErrorController')
    //         ->setConstructorArgs([$request, $response])
    //         ->setMethods(null)
    //         ->getMock();
    // }
    //
    // public function testInitializeLoadRequestHandler() {
    //     $this->assertInstanceOf('Cake\Controller\Component\RequestHandlerComponent', $this->controller->RequestHandler);
    // }
    //
    // public function testBeforeRenderAllowAllOrigin() {
    //     $event = new Event('Controller.startup', $this->controller);
    //     $this->controller->beforeRender($event);
    //     $responsesHeader = $this->controller->response->header();
    //     $this->assertEquals('*', $responsesHeader['Access-Control-Allow-Origin']);
    // }
}
