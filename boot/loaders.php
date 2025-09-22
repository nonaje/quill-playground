<?php

return function (\Quill\Contracts\Container\ContainerInterface $container): void {
    /**
     * --------------------------------------------------
     * Load configuration files into memory
     * --------------------------------------------------
     */
    new \Quill\Loaders\ConfigurationFilesLoader($container)->load(CONFIG_DIR);

    /**
     * --------------------------------------------------
     * Load configuration files into memory
     * --------------------------------------------------
     */
    new \Quill\Loaders\DotEnvLoader($container)->load();

    /**
     * --------------------------------------------------
     * Register routes inside routes folder
     * --------------------------------------------------
     */
    new \Quill\Loaders\RouteFilesLoader($container)->load();
};
