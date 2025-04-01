<?php

/**
 * --------------------------------------------------
 * Application configuration
 * --------------------------------------------------
 */
return [
    /**
     * --------------------------------------------------
     * Framework lifecycle middlewares
     * --------------------------------------------------
     *
     * These middlewares together make up the core lifecycle
     * of request processing. You can modify, extend, or add
     * new ones as you wish. Make sure you've registered
     * them in the dependency container.
     *
     * The middlewares will be executed in the defined order.
     */
    'lifecycle' => [
        \Quill\Middleware\RouteFinderMiddleware::class,
        \Quill\Middleware\ExecuteGlobalUserDefinedMiddlewares::class,
        \Quill\Middleware\ExecuteRouteMiddlewares::class,
    ],

    /**
     * --------------------------------------------------
     * User defined global middlewares
     * --------------------------------------------------
     *
     * These middlewares will be executed by "ExecuteGlobalUserDefinedMiddlewares"
     * in the order in which they are defined.
     */
    'middlewares' => [
        // Example middleware
        fn ($req, $next) => $next($req->withAttribute('foo', 'bar')),
    ],
];
