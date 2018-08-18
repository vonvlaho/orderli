<?php

/**
 * Copyright notice
 *
 * (c) 2018
 * Frederic von Vlahovits fvv@posteo.de
 *
 * Licensed under The MIT License (MIT)
 */

namespace FVV\Orderli\Controller;

use FVV\Orderli\Service\MailService;
use FVV\Orderli\Service\PDOService;
use FVV\Orderli\Service\CartStorageService;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class OrderController
{

    protected $container;

    protected $view;

    protected $pdoService;

    protected $mailService;

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
        $this->mailService = new MailService();
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function registerAction(Request $request, Response $response, array $args)
    {
        $baseUrl = $request->getUri()->getBaseUrl();

        // fetch form input
        $driverName = $request->getParam('driverName');
        $driverEmail = $request->getParam('driverEmail');
        $recipientEmail = preg_split("/[\s,;]+/", $request->getParam('recipientEmail'));
        $orderTime = $request->getParam('orderTime');
        $restaurantId = (int)$request->getParam('restaurant');
        $restaurant = $this->pdoService->getRestaurant($restaurantId);

        if ($this->pdoService->validateMailAdressess($recipientEmail)) {
            // generate order hash
            $hash = hash('md4', $driverEmail . date('YmdHis'));

            // convert input time to db format
            $expireTime = date('Y-m-d H:i:s', strtotime($orderTime));

            // insert order sessions params into db
            $this->pdoService->initiateOrder(
                $hash,
                $restaurantId,
                $driverName,
                $driverEmail,
                implode(",", $recipientEmail),
                $expireTime
            );

            // prepare initiation mail
            $mailSubject = 'Open Order';
            $mailHtml = $this->view->fetch('template/mail/orderRegister.twig', [
                'driver' => $driverName,
                'restaurant' => $restaurant,
                'time' => $orderTime,
                'hash' => $hash,
                'baseUrl' => $baseUrl
            ]);
            $mailTxt = $this->view->fetch('template/mail/txt/orderRegister.twig', [
                'driver' => $driverName,
                'restaurant' => $restaurant,
                'time' => $orderTime,
                'hash' => $hash,
                'baseUrl' => $baseUrl
            ]);

            $driverMailHtml = $this->view->fetch('template/mail/orderRegister.twig', [
                'driverMail' => true,
                'driver' => $driverName,
                'restaurant' => $restaurant,
                'time' => $orderTime,
                'hash' => $hash,
                'baseUrl' => $baseUrl
            ]);
            $driverMailTxt = $this->view->fetch('template/mail/txt/orderRegister.twig', [
                'driverMail' => true,
                'restaurant' => $restaurant,
                'time' => $orderTime,
                'hash' => $hash,
                'baseUrl' => $baseUrl
            ]);

            $this->mailService->sendMail($recipientEmail, $mailSubject, $mailHtml, $mailTxt, true);
            $this->mailService->sendMail($driverEmail, $mailSubject, $driverMailHtml, $driverMailTxt);

            return $response->withRedirect($this->container->get('router')->pathFor('order.select', ['hash' => $hash]));
        }

        $error = true;

        $restaurants = $this->pdoService->getRestaurants();

        return $this->view->render(
            $response,
            'template/index.twig',
            ['restaurants' => $restaurants,
            'error' => $error]
        );
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
    public function selectAction(Request $request, Response $response, array $args)
    {

        if ($this->pdoService->validateHash($args['hash'])) {
            if ($this->pdoService->validateSession($args['hash'])) {
                // fetch the id of the restaurant that was selected on registration
                $restaurantId = $this->pdoService->getOrderRestaurant($args['hash']);

                // fetch the whole menu of the selected restaurant
                $menu = $this->pdoService->fetchMenu($restaurantId);

                // fetch array with whole restaurant information
                $restaurant = $this->pdoService->getRestaurant($restaurantId);

                $cartCount = CartStorageService::count();

                return $this->view->render(
                    $response,
                    'template/menu.twig',
                    [
                        'menu' => $menu,
                        'cartCount' => $cartCount,
                        'restaurant' => $restaurant,
                        'hash' => $args['hash']
                    ]
                );
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
    public function submitAction(Request $request, Response $response, array $args)
    {

        if ($this->pdoService->validateHash($args['hash'])) {
            if ($this->pdoService->validateSession($args['hash'])) {
                if (CartStorageService::count() > 0) {
                    // Prepare full order details
                    $basket = CartStorageService::get();
                    $sum = $basket['sum'];
                    unset($basket['sum']);
                    $menuIds = array_keys($basket);

                    $ordererName = $request->getParam('orderer');
                    $ordererMail = $request->getParam('email');

                    // fetch extras string and check length
                    $extras = $request->getParam('extras');
                    $extras = (strlen($extras) > 100) ? substr($extras, 0, 100).'...' : $extras;

                    // insert full order details for every ordered dish into orders table
                    foreach ($menuIds as $menuId) {
                        $this->pdoService->placeOrder($args['hash'], $menuId, $ordererName, $ordererMail, $extras);
                    }

                    // prepare order mail
                    $restaurant = $this->pdoService->getRestaurant($this->pdoService->getOrderRestaurant($args['hash']));

                    $mailSubject = 'Hurray, you placed your order!';
                    $mailHtml = $this->view->fetch('template/mail/orderShow.twig', [
                        'basket' => $basket,
                        'sum' => $sum,
                        'restaurant' => $restaurant,
                        'extras' => $extras
                    ]);

                    $mailTxt = $this->view->fetch('template/mail/txt/orderShow.twig', [
                        'basket' => $basket,
                        'sum' => $sum,
                        'restaurant' => $restaurant,
                        'extras' => $extras
                    ]);

                    $this->mailService->sendMail($ordererMail, $mailSubject, $mailHtml, $mailTxt);

                    CartStorageService::reset();

                    return $this->view->render($response, 'template/success.twig');
                } else {
                    return $response->withRedirect(
                        $this->container->get('router')->pathFor(
                            'order.select',
                            ['hash' => $args['hash']]
                        )
                    );
                }
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
    public function checkAction(Request $request, Response $response, array $args)
    {
        if ($this->pdoService->validateHash($args['hash'])) {
            if ($this->pdoService->validateSession($args['hash'])) {
                $order = $this->pdoService->fetchOrder($args['hash']);
                // remove restaurant, which is the first
                // element of the returned array,
                // from that array and save it to var
                $restaurant = implode(array_splice($order, 0, 1));
                array_splice($order, 0, 2);
                $orderSum = implode(array_splice($order, 0, 1));
                return $this->view->render($response, 'template/orderShow.twig', [
                    'order' => $order,
                    'restaurant' => $restaurant,
                    'sum' => $orderSum
                ]);
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
    public function closeAction(Request $request, Response $response, array $args)
    {

        if ($this->pdoService->validateHash($args['hash'])) {
            if ($this->pdoService->validateSession($args['hash'])) {
                $order = $this->pdoService->fetchOrder($args['hash']);

                if ($order['expire_time'] <= date('Y-m-d H:i:s') || $request->getParam('close') === '!') {
                    // remove restaurant and driver_mail, which are the first
                    // and second element of the returned array,
                    // from that array and save it to vars
                    $restaurant = implode(array_splice($order, 0, 1));
                    $driverEmail = implode(array_splice($order, 0, 1));

                    // remove expiretime from array
                    array_splice($order, 0, 1);

                    $orderSum = implode(array_splice($order, 0, 1));

                    // prepare full order mail
                    $mailSubject = 'Your order session has closed';

                    $mailHtml = $this->view->fetch('template/mail/fullOrderShow.twig', [
                        'order' => $order,
                        'restaurant' => $restaurant,
                        'sum' => $orderSum
                    ]);

                    $mailTxt = $this->view->fetch('template/mail/txt/fullOrderShow.twig', [
                        'order' => $order,
                        'restaurant' => $restaurant,
                        'sum' => $orderSum
                    ]);

                    $this->mailService->sendMail($driverEmail, $mailSubject, $mailHtml, $mailTxt);

                    // Set order_session entry state to 'closed'
                    $this->pdoService->closeOrder($args['hash']);

                    return $this->view->render($response, 'template/orderShow.twig', [
                        'order' => $order,
                        'restaurant' => $restaurant,
                        'sum' => $orderSum
                    ]);
                } else {
                    return $this->view->render($response, 'template/confirmClose.twig', ['hash' => $args['hash']]);
                }
            } else {
                return $this->view->render($response, 'template/noSession.twig');
            }
        } else {
            return $response->withRedirect($this->container->get('router')->pathFor('index'));
        }
    }
}