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
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IndexController
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
     *
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function indexAction(Request $request, Response $response)
    {
        $restaurants = $this->pdoService->getRestaurants();

        return $this->view->render($response, 'template/index.twig', ['restaurants' => $restaurants]);
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function privacyAction(Request $request, Response $response)
    {
        return $this->view->render($response, 'template/privacy.twig');
    }
}