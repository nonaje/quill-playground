<?php

declare(strict_types=1);

return function (\Quill\Contracts\Container\ContainerInterface $container): void {
    /**
     * --------------------------------------------------
     * Register Quill services and dependencies
     * --------------------------------------------------
     */
    registerQuillBindings($container);
    registerQuillSingletons($container);

    /**
     * --------------------------------------------------
     * Application Dependencies
     * --------------------------------------------------
     */

};

function registerQuillSingletons(\Quill\Contracts\Container\ContainerInterface $container): void
{
    $container->singleton(
        id: \Quill\Contracts\Router\RouterInterface::class,
        resolver: fn (\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Router\Router($container->get(\Quill\Contracts\Middleware\MiddlewareFactoryInterface::class))
    );

    $container->singleton(
        \Quill\Contracts\Configuration\ConfigurationInterface::class,
        fn(\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Configuration\Config()
    );

    $container->singleton(
        id: \Quill\Contracts\Response\ResponseSenderInterface::class,
        resolver: fn(\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Response\ResponseSender()
    );

    $container->singleton(
        id: \Quill\Contracts\Support\PathResolverInterface::class,
        resolver: fn (\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Support\Path()
    );

    $container->singleton(
        id: \Quill\Handler\RequestHandler::class,
        resolver: fn (\Quill\Contracts\Container\ContainerInterface $container) => new ReflectionClass(\Quill\Handler\RequestHandler::class)
            ->newLazyGhost(fn (\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Handler\RequestHandler(
                $container,
                $container->get(\Quill\Contracts\Response\ResponseInterface::class),
                $container->get(\Quill\Contracts\Request\RequestInterface::class),
                $container->get(\Quill\Contracts\Router\RouterInterface::class)
        ))
    );

    $container->singleton(
        id: \Quill\Middleware\ExecuteGlobalUserDefinedMiddlewares::class,
        resolver: fn (\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Middleware\ExecuteGlobalUserDefinedMiddlewares(
            $container->get(\Quill\Contracts\Middleware\MiddlewarePipelineInterface::class),
            $container->get(\Quill\Contracts\Router\MiddlewareStoreInterface::class)
        )
    );

    $container->singleton(
        id: \Quill\Contracts\Middleware\MiddlewareFactoryInterface::class,
        resolver: fn (\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Router\MiddlewareFactory($container)
    );
}

function registerQuillBindings(\Quill\Contracts\Container\ContainerInterface $container): void
{
    $container->register(
        id: \Psr\Http\Message\RequestInterface::class,
        resolver: fn(\Quill\Contracts\Container\ContainerInterface $container) => $container->get(\Nyholm\Psr7Server\ServerRequestCreatorInterface::class)->fromGlobals()
    );

    $container->register(
        id: \Psr\Http\Message\ResponseInterface::class,
        resolver: fn(\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Factory\Psr7\ResponseFactory()->createResponse()
    );

    $container->register(
        id: \Quill\Contracts\ErrorHandler\ErrorHandlerInterface::class,
        resolver: fn(\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Handler\Error\JsonErrorHandler(
            $container->get(\Quill\Contracts\Response\ResponseInterface::class),
            $container->get(\Quill\Contracts\Response\ResponseSenderInterface::class)
        )
    );

    $container->register(
        \Quill\Contracts\Router\RouteStoreInterface::class,
        fn(\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Router\RouteStore()
    );

    $container->register(
        id: \Quill\Contracts\Middleware\MiddlewarePipelineInterface::class,
        resolver: fn(\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Handler\MiddlewarePipelineHandler()
    );

    $container->register(
        id: \Quill\Contracts\Router\MiddlewareStoreInterface::class,
        resolver: fn(\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Router\MiddlewareStore($container)
    );

    $container->register(
        id: \Quill\Contracts\Response\ResponseInterface::class,
        resolver: fn(\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Response\Response()
    );

    $container->register(
        id: \Quill\Contracts\Request\RequestInterface::class,
        resolver: fn(\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Request\Request($container->get(\Psr\Http\Message\RequestInterface::class))
    );

    $container->register(
        id: \Quill\Middleware\RouteFinderMiddleware::class,
        resolver: fn (\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Middleware\RouteFinderMiddleware($container->get(\Quill\Contracts\Router\RouterInterface::class))
    );

    $container->register(
        id: \Quill\Middleware\RouteFinderMiddleware::class,
        resolver: fn (\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Middleware\RouteFinderMiddleware($container->get(\Quill\Contracts\Router\RouterInterface::class))
    );

    $container->register(
        id: \Quill\Middleware\ExecuteRouteMiddlewares::class,
        resolver: fn (\Quill\Contracts\Container\ContainerInterface $container) => new \Quill\Middleware\ExecuteRouteMiddlewares($container->get(\Quill\Contracts\Middleware\MiddlewarePipelineInterface::class))
    );

    $container->register(
        id: \Nyholm\Psr7Server\ServerRequestCreatorInterface::class,
        resolver: function (\Quill\Contracts\Container\ContainerInterface $container) {
            $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
            return new \Nyholm\Psr7Server\ServerRequestCreator(
                serverRequestFactory: $psr17Factory,
                uriFactory: $psr17Factory,
                uploadedFileFactory: $psr17Factory,
                streamFactory: $psr17Factory
            );
        }
    );
}
