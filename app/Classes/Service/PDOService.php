<?php
/**
 * *
 *  * Copyright notice
 *  *
 *  * (c) 2018
 *  * Frederic von Vlahovits fvv@posteo.de
 *  *
 *  * Licensed under The MIT License (MIT)
 *
 */

namespace FVV\Orderli\Service;

use PDO;
use PDOException;


class PDOService extends PDO
{

    public function __construct()
    {
        parent::__construct('mysql:host=localhost;dbname=orderli;charset=utf8mb4;', 'root', 'vagrant', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public function run($stmt)
    {
        try {
            $stmt->execute();

            return $stmt;

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function fetchMenu(int $id)
    {
        $stmt = $this->prepare('SELECT menu.id AS id, 
        menu.menu_id, 
        menu.name, 
        menu.description, 
        menu.price, 
        menu_categories.name AS category_name,
        menu_categories.id AS category_id,
        menu_categories.description AS category_description
        FROM menu 
        LEFT JOIN menu_categories ON menu.menu_category = menu_categories.id
        WHERE menu.menu_category > 0
        AND menu.restaurant_id = :restaurantId');

        $stmt->bindParam(':restaurantId', $id);

        $this->run($stmt);

        $dishes = $stmt->fetchAll();

        $categories = array_column($dishes, 'category_name', 'category_id');
        $menu = [];

        foreach ($categories as $key => $val) {
            foreach ($dishes as $dish) {
                if ($key == $dish['category_id']) {
                    $menu[$val][] = $dish;
                }
            }
        }

        return $menu;
    }

    public function getDish(int $dishId)
    {
        $stmt = $this->prepare('SELECT menu.id AS id, 
        restaurants.id AS restaurant_id, 
        restaurants.name as restaurant_name, 
        menu.menu_id, 
        menu.name, 
        menu.description as description, 
        menu.price, 
        menu_categories.name AS category_name,
        menu_categories.id AS category_id,
        menu_categories.description AS category_description
        FROM restaurants 
        LEFT JOIN menu ON restaurants.id = menu.restaurant_id
        LEFT JOIN menu_categories ON menu.menu_category = menu_categories.id
        WHERE menu.menu_category > 0
        AND menu.id = :dishId');

        $stmt->bindParam(':dishId', $dishId);

        $this->run($stmt);

        return $stmt->fetch();
    }

    public function getRestaurants()
    {
        $stmt = $this->prepare('SELECT id, name, adr_street, adr_number, adr_zip, adr_city
        FROM restaurants');

        $this->run($stmt);

        return $stmt->fetchAll();
    }

    public function getRestaurant(int $id)
    {
        $stmt = $this->prepare('SELECT *
        FROM restaurants
        WHERE restaurants.id = :id');

        $stmt->bindParam(':id', $id);

        $this->run($stmt);

        return $stmt->fetch();
    }

    public function initiateOrder(
        string $hash,
        int $restaurantId,
        string $driverName,
        string $driverEmail,
        string $recipientEmail,
        string $expireTime
    ) {
        $stmt = $this->prepare(
            "INSERT INTO order_sessions 
            (hash, restaurant_id, driver_name, driver_mail, recepient_mail, expire_time) 
            VALUES (:hash, :restaurant_id, :driver_name, :driver_mail, :recepient_mail, :expire_time)"
        );

        $stmt->bindParam(':hash', $hash);
        $stmt->bindParam(':restaurant_id', $restaurantId);
        $stmt->bindParam(':driver_name', $driverName);
        $stmt->bindParam(':driver_mail', $driverEmail);
        $stmt->bindParam(':recepient_mail', $recipientEmail);
        $stmt->bindParam(':expire_time', $expireTime);

        $this->run($stmt);
    }

    public function getOrderRestaurant(string $hash)
    {
        $stmt = $this->prepare("SELECT restaurant_id FROM order_sessions WHERE hash = :hash");

        $stmt->bindParam(':hash', $hash);

        $this->run($stmt);

        return (int)$stmt->fetch()['restaurant_id'];
    }

    public function fetchOrderSession(string $hash)
    {
        $stmt = $this->prepare("SELECT * FROM order_sessions WHERE hash = :hash");

        $stmt->bindParam(':hash', $hash);

        $this->run($stmt);

        return $stmt->fetch();
    }

    public function validateHash(string $hash)
    {
        $session = $this->fetchOrderSession($hash);

        if ($session['hash']) {
            return true;
        }

        return false;
    }

    public function validateSession(string $hash)
    {
        $session = $this->fetchOrderSession($hash);

        if ((int) $session['closed'] === 0) {
            return true;
        }

        return false;
    }

    public function validateMailAdressess(array $mailAdresses)
    {
        if (count($mailAdresses) < 51) {
            return false;
        }

        foreach ($mailAdresses as $mailA) {
            if (!filter_var($mailA, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
        }

        return true;
    }


    public function placeOrder(string $hash, int $menuId, string $ordererName, string $ordererMail, string $extras)
    {
        $stmt = $this->prepare(
            "INSERT INTO orders (hash, menu_id, orderer_name, orderer_mail, extras) 
            VALUES (:hash, :menu_id, :orderer_name, :orderer_mail, :extras)"
        );

        $stmt->bindParam(':hash', $hash);
        $stmt->bindParam(':menu_id', $menuId);
        $stmt->bindParam(':orderer_name', $ordererName);
        $stmt->bindParam(':orderer_mail', $ordererMail);
        $stmt->bindParam(':extras', $extras);

        $this->run($stmt);
    }

    public function fetchOrder(string $hash)
    {
        $stmt = $this->prepare('SELECT order_sessions.driver_mail as driver_mail,
        order_sessions.expire_time as expire_time,
        restaurants.id as restaurant_id, 
        restaurants.name as restaurant_name, 
        orders.orderer_name, 
        orders.menu_id,
        orders.extras,
        menu.name, 
        menu.description, 
        menu.price
        FROM orders
        LEFT JOIN menu ON orders.menu_id = menu.id
        LEFT JOIN order_sessions ON orders.hash = order_sessions.hash
        LEFT JOIN restaurants ON menu.restaurant_id = restaurants.id
        WHERE orders.hash = :hash
        ORDER BY orderer_name ASC, orders.menu_id ASC');

        $stmt->bindParam(':hash', $hash);

        $this->run($stmt);

        $order = $stmt->fetchAll();

        $orderer = array_column($order, 'orderer_name', 'orderer_name');

        $restaurant = implode(array_column($order, 'restaurant_name', 'restaurant_id'));
        $driverMail = implode(array_column($order, 'driver_mail', 'driver_mail'));
        $expireTime = implode(array_column($order, 'expire_time', 'expire_time'));

        $fullOrder = [];

        $fullOrder['restaurant'] = $restaurant;
        $fullOrder['driver_mail'] = $driverMail;
        $fullOrder['expire_time'] = $expireTime;

        foreach ($orderer as $key => $val) {
            foreach ($order as $orderEntry) {
                if ($key == $orderEntry['orderer_name']) {
                    $fullOrder['sum'] += $orderEntry['price'];
                    $fullOrder[$val][] = $orderEntry;
                }
            }
        }

        return $fullOrder;
    }

    public function closeOrder(string $hash)
    {
        $stmt = $this->prepare('UPDATE order_sessions SET closed = :closed
        WHERE hash = :hash');

        $stmt->bindParam(':closed', $closed = 1);
        $stmt->bindParam(':hash', $hash);

        $this->run($stmt);
    }
}