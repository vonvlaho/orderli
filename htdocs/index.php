<?php

/**
 * Copyright notice
 *
 * (c) 2018
 * Frederic von Vlahovits fvv@posteo.de
 *
 * Licensed under The MIT License (MIT)
 */

use \Slim\App as App;
use FVV\Orderli\Controller\OrderController;
use FVV\Orderli\Controller\IndexController;
use FVV\Orderli\Controller\MenuController;
use FVV\Orderli\Controller\CartController;

session_cache_limiter(false);
session_start();

require '../vendor/autoload.php';
require '../app/Configuration/SlimConfig.php';

$app = new App($config);
$container = $app->getContainer();

require '../app/Configuration/Container.php';

// routes
$app->get('/', IndexController::class . ':indexAction')->setName('index');
$app->get('/privacy', IndexController::class . ':privacyAction')->setName('privacy');


$app->post('/register', OrderController::class . ':registerAction')->setName('order.open');
$app->get('/order/{hash}', OrderController::class . ':selectAction')->setName('order.select');
$app->post('/order/submit/{hash}', OrderController::class . ':submitAction')->setName('order.submit');
$app->get('/order/close/{hash}', OrderController::class . ':closeAction')->setName('order.close');

$app->get('/menus', MenuController::class . ':listMenusAction')->setName('menus');
$app->get('/menu/{id}', MenuController::class . ':menuAction')->setName('menu');

$app->get('/cart/{hash}', CartController::class . ':showAction')->setName('cart.show');
$app->get('/cart/add/{hash}', CartController::class . ':addAction')->setName('cart.add');
$app->get('/cart/delete/{hash}', CartController::class . ':deleteAction')->setName('cart.delete');
$app->get('/cart/reset/{hash}', CartController::class . ':resetAction')->setName('cart.reset');
$app->get('/cart/checkout/{hash}', CartController::class . ':checkoutAction')->setName('cart.checkout');




// Run app
$app->run();