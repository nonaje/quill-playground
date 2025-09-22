<?php

declare(strict_types=1);

use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;
use Quill\Contracts\Container\ContainerInterface;

return function (ContainerInterface $container): void {
    /**
     * --------------------------------------------------
     * Register Quill services and dependencies
     * --------------------------------------------------
     */
    registerQuillSingletons($container);
    registerQuillBindings($container);

    /**
     * --------------------------------------------------
     * Application Dependencies
     * --------------------------------------------------
     */

};

function registerQuillSingletons(ContainerInterface $container): void
{
    $container->singleton(
        id: \Quill\Contracts\Router\RouterInterface::class,
        resolver: fn (ContainerInterface $container) => new \Quill\Router\Router($container->get(\Quill\Contracts\Middleware\MiddlewareFactoryInterface::class))
    );

    $container->singleton(
        \Quill\Contracts\Configuration\ConfigurationInterface::class,
        fn(ContainerInterface $container) => new \Quill\Configuration\Config()
    );

    $container->singleton(
        id: \Quill\Contracts\Response\ResponseSenderInterface::class,
        resolver: fn(ContainerInterface $container) => new \Quill\Response\ResponseSender()
    );

    $container->singleton(
        id: \Quill\Contracts\Support\PathResolverInterface::class,
        resolver: fn (ContainerInterface $container) => new \Quill\Support\Path()
    );

    $container->singleton(
        id: \Quill\Handler\RequestHandler::class,
        resolver: fn (ContainerInterface $container) => new \Quill\Handler\RequestHandler()
    );

    $container->singleton(
        id: \Quill\Middleware\ExecuteGlobalUserDefinedMiddlewares::class,
        resolver: fn (ContainerInterface $container) => new \Quill\Middleware\ExecuteGlobalUserDefinedMiddlewares(
            $container->get(\Quill\Contracts\Middleware\MiddlewarePipelineInterface::class),
            $container->get(\Quill\Contracts\Router\MiddlewareStoreInterface::class)
        )
    );

    $container->singleton(
        id: \Quill\Contracts\Middleware\MiddlewareFactoryInterface::class,
        resolver: fn (ContainerInterface $container) => new \Quill\Router\MiddlewareFactory($container)
    );
}

function registerQuillBindings(ContainerInterface $container): void
{
    $container->register(
        id: \Quill\Contracts\ErrorHandler\ErrorHandlerInterface::class,
        resolver: fn(ContainerInterface $container) => new \Quill\Handler\Error\JsonErrorHandler(
            $container->get(\Quill\Contracts\Response\ResponseInterface::class),
            $container->get(\Quill\Contracts\Response\ResponseSenderInterface::class)
        )
    );

    $container->register(
        \Quill\Contracts\Router\RouteStoreInterface::class,
        fn(ContainerInterface $container) => new \Quill\Router\RouteStore()
    );

    $container->register(
        id: \Quill\Contracts\Middleware\MiddlewarePipelineInterface::class,
        resolver: fn(ContainerInterface $container) => new \Quill\Handler\MiddlewarePipelineHandler()
    );

    $container->register(
        id: \Quill\Contracts\Router\MiddlewareStoreInterface::class,
        resolver: fn(ContainerInterface $container) => new \Quill\Router\MiddlewareStore($container)
    );

    $container->register(
        id: \Quill\Contracts\Response\ResponseInterface::class,
        resolver: fn(ContainerInterface $container) => new \Quill\Response\Response($container->get(\Psr\Http\Message\ResponseInterface::class))
    );

    $container->register(
        id: \Quill\Contracts\Request\RequestInterface::class,
        resolver: fn(ContainerInterface $container) => new \Quill\Request\Request($container->get(\Psr\Http\Message\RequestInterface::class))
    );

    $container->register(
        id: \Quill\Middleware\RouteFinderMiddleware::class,
        resolver: fn (ContainerInterface $container) => new \Quill\Middleware\RouteFinderMiddleware($container->get(\Quill\Contracts\Router\RouterInterface::class))
    );

    $container->register(
        id: \Quill\Middleware\RouteFinderMiddleware::class,
        resolver: fn (ContainerInterface $container) => new \Quill\Middleware\RouteFinderMiddleware($container->get(\Quill\Contracts\Router\RouterInterface::class))
    );

    $container->register(
        id: \Quill\Middleware\ExecuteRouteMiddlewares::class,
        resolver: fn (ContainerInterface $container) => new \Quill\Middleware\ExecuteRouteMiddlewares($container->get(\Quill\Contracts\Middleware\MiddlewarePipelineInterface::class))
    );

    $container->register(
        id: ServerRequestCreatorInterface::class,
        resolver: function (ContainerInterface $container) {
            $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
            return new ServerRequestCreator(
                serverRequestFactory: $psr17Factory,
                uriFactory: $psr17Factory,
                uploadedFileFactory: $psr17Factory,
                streamFactory: $psr17Factory
            )->fromGlobals();
        }
    );

    $container->register(
        id: \Psr\Http\Message\RequestInterface::class,
        resolver: fn(ContainerInterface $container) => $container->get(ServerRequestCreatorInterface::class)->fromGlobals()
    );

    $container->register(
        id: \Psr\Http\Message\ResponseInterface::class,
        resolver: fn(ContainerInterface $container) => \Quill\Factory\Psr7\Psr7Factory::responseFactory()->createResponse()
    );
}
