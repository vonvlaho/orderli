<?php

/**
 * Copyright notice
 *
 * (c) 2018
 * Frederic von Vlahovits fvv@posteo.de
 *
 * Licensed under The MIT License (MIT)
 */

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../views/', [
        'cache' => false,
        'debug' => true,
        'auto_reload' => true
    ]);

    $view->addExtension(new \Twig_Extension_Debug());

    // Instantiate and add Slim specific extension
    $basePath = $container->get('request')->getUri()->getBasePath();
    $view->addExtension(new \Slim\Views\TwigExtension($container->get('router'), $basePath));

    return $view;
};