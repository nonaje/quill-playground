<?php

use Quill\Contracts\Router\RouterInterface;

return function(RouterInterface $route) {
    $route->group('admin', function (RouterInterface $route) {
        $route->group('dashboard', function (RouterInterface $route) {
            $route->get('asd', function ($req, $res) {
                return $res->plain('De uña');
            }, middlewares: [fn ($req, $next) => $next($req->withAttribute('zoo', 'lion'))]);
        });
    });

    $route->get('/:id', function ($req, $res, $params) {
        return $res->json([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'id' => $params['id'],
        ]);
    });
};
