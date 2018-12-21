<?php
namespace Cors\Routing\Middleware;

use Cake\Core\Configure;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CorsMiddleware
{

    /**
     * PHPCS docblock fix needed!
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next) {
        if ($request->getHeader('Origin')) {
            $response = $response
                ->withHeader('Access-Control-Allow-Origin', $this->_allowOrigin($request))
                ->withHeader('Access-Control-Allow-Credentials', $this->_allowCredentials())
                ->withHeader('Access-Control-Max-Age', $this->_maxAge())
                ->withHeader('Access-Control-Expose-Headers', $this->_exposeHeaders())
            ;

            if (strtoupper($request->getMethod()) === 'OPTIONS') {
                $response = $response
                    ->withHeader('Access-Control-Allow-Headers', $this->_allowHeaders($request))
                    ->withHeader('Access-Control-Allow-Methods', $this->_allowMethods())
                ;
                return $response;
            }
        }

        return $next($request, $response);
    }

    /**
     * PHPCS docblock fix needed!
     */
    private function _allowOrigin($request) {
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
     * PHPCS docblock fix needed!
     */
    private function _allowCredentials() {
        return (Configure::read('Cors.AllowCredentials')) ? 'true' : 'false';
    }

    /**
     * PHPCS docblock fix needed!
     */
    private function _allowMethods() {
        return implode(', ', (array) Configure::read('Cors.AllowMethods'));
    }

    /**
     * PHPCS docblock fix needed!
     */
    private function _allowHeaders($request) {
        $allowHeaders = Configure::read('Cors.AllowHeaders');

        if ($allowHeaders === true) {
            return $request->getHeader('Access-Control-Request-Headers');
        }

        return implode(', ', (array) $allowHeaders);
    }

    /**
     * PHPCS docblock fix needed!
     */
    private function _exposeHeaders() {
        $exposeHeaders = Configure::read('Cors.ExposeHeaders');

        if (is_string($exposeHeaders) || is_array($exposeHeaders)) {
            return implode(', ', (array) $exposeHeaders);
        }

        return '';
    }

    /**
     * PHPCS docblock fix needed!
     */
    private function _maxAge() {
        $maxAge = (string) Configure::read('Cors.MaxAge');

        return ($maxAge) ?: '0';
    }
}
