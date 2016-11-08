<?php
namespace Cors\Routing\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Cake\Core\Configure;

class CorsMiddleware {

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next) {

        if ($request->getHeader('Origin')) {
            $response = $response->withHeader('Access-Control-Allow-Origin', $this->_allowOrigin($request))
                ->withHeader('Access-Control-Allow-Credentials', 'true')
                ->withHeader('Access-Control-Max-Age', '0');    // no cache
                // ->withHeader('Access-Control-Max-Age', '86400');    // cache for 1 day

            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                $method  = $request->getHeader('Access-Control-Request-Method');
                $response = $response->withHeader('Access-Control-Allow-Headers', $request->getHeader('Access-Control-Request-Headers'))
                    ->withHeader('Access-Control-Allow-Methods', empty($method) ? 'GET, POST, PUT, DELETE' : $method);

                return $response;
            }
        }

        return $next($request, $response);
    }

    private function _allowOrigin($request) {
        $allowOrigin = Configure::read('Cors.AllowOrigin');
        $origin = $request->getHeader('Origin');

        if ($allowOrigin === true) {
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

        return (string) $allowOrigin;
    }
}
