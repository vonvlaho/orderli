<?php

namespace FVV\Orderli\Controller;

/**
 * Copyright notice
 *
 * (c) 2018
 * Frederic von Vlahovits fvv@posteo.de
 *
 * Licensed under The MIT License (MIT)
 */

use FVV\Orderli\Service\PDOService;
use FVV\Orderli\Service\CartStorageService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class CartController
{

    protected $container;

    protected $view;

    protected $pdoService;

    /**
     * PageController constructor.
     *
     * @param ContainerInterface $container
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->view = $this->container->get('view');
        $this->pdoService = new PDOService();

    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function showAction(Request $request, Response $response, array $args)
    {
        if ($this->pdoService->validateHash($args['hash'])) {

            if ($this->pdoService->validateSession($args['hash'])) {

                $basket = CartStorageService::get();
                $sum = $basket['sum'];
                unset($basket['sum']);

                return $this->view->render($response, 'template/cart.twig',
                    ['basket' => $basket, 'hash' => $args['hash'], 'sum' => $sum]);

            } else {

                return $this->view->render($response, 'template/noSession.twig');

            }

        } else {

            return $response->withRedirect($this->container->get('router')->pathFor('index'));
        }

    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function addAction(Request $request, Response $response, array $args)
    {

        $item = $request->getParam('cart');

        if ((int)$item) {
            CartStorageService::add($item);
        }

        return $response->withRedirect($this->container->get('router')->pathFor('order.select',
            ['hash' => $args['hash']]));

    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function deleteAction(Request $request, Response $response, array $args)
    {

        $item = $request->getParam('delete');

        if ((int)$item) {
            CartStorageService::delete($item);
        }

        return $response->withRedirect($this->container->get('router')->pathFor('cart.show',
            ['hash' => $args['hash']]));

    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function resetAction(Request $request, Response $response, array $args)
    {

        CartStorageService::reset();

        return $response->withRedirect($this->container->get('router')->pathFor('order.select',
            ['hash' => $args['hash']]));

    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function checkoutAction(Request $request, Response $response, array $args)
    {
        if ($this->pdoService->validateHash($args['hash'])) {
            if ($this->pdoService->validateSession($args['hash'])) {
                if (CartStorageService::count() > 0) {

                    $basket = CartStorageService::get();
                    $sum = $basket['sum'];
                    unset($basket['sum']);

                    return $this->view->render($response, 'template/checkout.twig',
                        ['basket' => $basket, 'hash' => $args['hash'], 'sum' => $sum, 'extras' => $extras]);
                } else {
                    return $response->withRedirect($this->container->get('router')->pathFor('order.select',
                        ['hash' => $args['hash']]));
                }
            } else {
                return $this->view->render($response, 'template/noSession.twig');
            }

        } else {

            return $response->withRedirect($this->container->get('router')->pathFor('index'));
        }

    }

}