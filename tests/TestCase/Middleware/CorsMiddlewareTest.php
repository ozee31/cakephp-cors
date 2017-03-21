<?php
namespace Cors\TestCase\Middleware;

use PHPUnit\Framework\TestCase;
use Cake\Http\ServerRequestFactory;
use Cake\Network\Response;
use Cors\Routing\Middleware\CorsMiddleware;
use Cake\Core\Configure;

class CorsMiddlewareTest extends TestCase {

    private $server = [];

    const BASE_ORIGIN = 'http://test.com';

    public function setUp()
    {
        parent::setUp();
        $this->server = [
            'REQUEST_URI' => '/test',
            'HTTP_ORIGIN' => 'http://test.com',
            'REQUEST_METHOD' => 'OPTIONS',
        ];
        Configure::load('Cors.default', 'default');
        Configure::write('Cors',  (array) Configure::consume('Cors-default'));
    }

    private function _setServer(Array $server)
    {
        $this->server = array_merge($this->server, $server);
    }

    private function _sendRequest()
    {
        $request = ServerRequestFactory::fromGlobals($this->server);
        $response = new Response();
        $middleware = new CorsMiddleware();
        $next = function ($request, $response) {
            return $response;
        };
        $response = $middleware($request, $response, $next);
        return $response;
    }

    public function testNoExposeAllCorsHeadersIfNotAOptionsRequest() {
        $this->_setServer(['REQUEST_METHOD' => 'GET']);
        $response = $this->_sendRequest();
        $headersKeys = array_keys($response->header());

        $this->assertContains('Access-Control-Allow-Origin', $headersKeys);
        $this->assertContains('Access-Control-Allow-Credentials', $headersKeys);
        $this->assertContains('Access-Control-Max-Age', $headersKeys);
        $this->assertNotContains('Access-Control-Allow-Headers', $headersKeys);
        $this->assertNotContains('Access-Control-Allow-Methods', $headersKeys);
        $this->assertNotContains('Access-Control-Expose-Headers', $headersKeys);
    }

    public function testDefaultValuesIfNotAOptionsRequest() {
        $this->_setServer(['REQUEST_METHOD' => 'GET']);
        $response = $this->_sendRequest();
        $headers = $response->header();

        $this->assertEquals(self::BASE_ORIGIN, $headers['Access-Control-Allow-Origin']);
        $this->assertEquals('true', $headers['Access-Control-Allow-Credentials']);
        $this->assertEquals(Configure::read('Cors.MaxAge'), $headers['Access-Control-Max-Age']);
    }

    public function testExposeAllCorsHeadersIfIsAOptionsRequest() {
        $response = $this->_sendRequest();
        $headersKeys = array_keys($response->header());

        $this->assertContains('Access-Control-Allow-Origin', $headersKeys);
        $this->assertContains('Access-Control-Allow-Credentials', $headersKeys);
        $this->assertContains('Access-Control-Max-Age', $headersKeys);
        $this->assertContains('Access-Control-Allow-Headers', $headersKeys);
        $this->assertContains('Access-Control-Allow-Methods', $headersKeys);
        $this->assertContains('Access-Control-Expose-Headers', $headersKeys);
    }

    public function testDefaultValuesIfIsAOptionsRequest() {
        $response = $this->_sendRequest();
        $headers = $response->header();

        $this->assertEquals([], $headers['Access-Control-Allow-Headers']);
        $this->assertEquals('GET, POST, PUT, PATCH, DELETE', $headers['Access-Control-Allow-Methods']);
        $this->assertEquals('', $headers['Access-Control-Expose-Headers']);
    }

    private function _sendRequestForOriginTest($originUrl, $allowUrl) {
        Configure::write('Cors.AllowOrigin', $allowUrl);
        $this->_setServer(['HTTP_ORIGIN' => $originUrl]);
        return $this->_sendRequest()->header()['Access-Control-Allow-Origin'];
    }

    public function testOriginDifferentAllKey() {
        $responseAllowOrigin = $this->_sendRequestForOriginTest(self::BASE_ORIGIN, '*');
        $this->assertEquals(self::BASE_ORIGIN, $responseAllowOrigin);

        $responseAllowOrigin = $this->_sendRequestForOriginTest('https://google.com', '*');
        $this->assertEquals('https://google.com', $responseAllowOrigin);

        $responseAllowOrigin = $this->_sendRequestForOriginTest('https://google.com', true);
        $this->assertEquals('https://google.com', $responseAllowOrigin);
    }

    public function testOriginIsForbidden() {
        $responseAllowOrigin = $this->_sendRequestForOriginTest('https://google.com', false);
        $this->assertEquals('', $responseAllowOrigin);
    }

    public function testOriginStringIsOk() {
        $responseAllowOrigin = $this->_sendRequestForOriginTest('https://google.com', 'https://google.com');
        $this->assertEquals('https://google.com', $responseAllowOrigin);
    }

    public function testOriginStringIsForbidden() {
        $responseAllowOrigin = $this->_sendRequestForOriginTest('https://google.com', 'https://bing.com');
        $this->assertEquals('https://bing.com', $responseAllowOrigin);
    }

    public function testOriginArrayIsOk() {
        $responseAllowOrigin = $this->_sendRequestForOriginTest('https://google.com', ['https://bing.com', 'https://google.com']);
        $this->assertEquals('https://google.com', $responseAllowOrigin);
    }

    public function testOriginArrayIsForbidden() {
        $responseAllowOrigin = $this->_sendRequestForOriginTest('https://duckduckgo.com', ['https://bing.com', 'https://google.com']);
        $this->assertEquals('', $responseAllowOrigin);
    }
}
