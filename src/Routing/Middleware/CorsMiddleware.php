<?php
namespace Cors\Routing\Middleware;

use Cake\Core\Configure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cake\Http\Response;

class CorsMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            if (!array_intersect($request->getHeader("Access-Control-Request-Method"), Configure::read('Cors.AllowMethods'))) {
                $response =  new Response([
                    'status' => 403,
                    'body' => 'Method Forbidden'
                ]);
            } else {
                $response =  new Response([
                    'status' => 200
                ]);
            }
        } else {
            $response = $handler->handle($request);
        }

        $response = $this->addHeaders($request, $response);

        return $response;
    }

    public function addHeaders(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($request->getHeader('Origin')) {
            $response = $response
                ->withHeader('Access-Control-Allow-Origin', $this->_allowOrigin($request))
                ->withHeader('Access-Control-Allow-Credentials', $this->_allowCredentials())
                ->withHeader('Access-Control-Max-Age', $this->_maxAge());

            if (strtoupper($request->getMethod()) === 'OPTIONS') {
                $response = $response
                    ->withHeader('Access-Control-Expose-Headers', $this->_exposeHeaders())
                    ->withHeader('Access-Control-Allow-Headers', $this->_allowHeaders($request))
                    ->withHeader('Access-Control-Allow-Methods', $this->_allowMethods());
            }

        }

        return $response;
    }


    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return array|string
     */
    private function _allowOrigin(ServerRequestInterface $request)
    {
        $allowOrigin = Configure::read('Cors.AllowOrigin');
        $origin = $request->getHeader('Origin');

        if ($allowOrigin === true || $allowOrigin === '*') {
            return $origin;
        }

        if (is_array($allowOrigin)) {
            $origin = (array) $origin;

            foreach ($origin as $o) {
                if (in_array($o, $allowOrigin)) {
                    return $origin;
                }
            }

            return '';
        }

        return (string)$allowOrigin;
    }

    /**
     * @return String
     */
    private function _allowCredentials(): String
    {
        return (Configure::read('Cors.AllowCredentials')) ? 'true' : 'false';
    }

    /**
     * @return String
     */
    private function _allowMethods(): String
    {
        return implode(', ', (array) Configure::read('Cors.AllowMethods'));
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return String
     */
    private function _allowHeaders(ServerRequestInterface $request): String
    {
        $allowHeaders = Configure::read('Cors.AllowHeaders');

        if ($allowHeaders === true) {
            return $request->getHeaderLine('Access-Control-Request-Headers');
        }

        return implode(', ', (array) $allowHeaders);
    }

    /**
     * @return String
     */
    private function _exposeHeaders(): String
    {
        $exposeHeaders = Configure::read('Cors.ExposeHeaders');

        if (is_string($exposeHeaders) || is_array($exposeHeaders)) {
            return implode(', ', (array) $exposeHeaders);
        }

        return '';
    }

    /**
     * @return String
     */
    private function _maxAge(): String
    {
        $maxAge = (string) Configure::read('Cors.MaxAge');

        return ($maxAge) ?: '0';
    }
}
