<?php

namespace FVV\Orderli\Service;

/**
 * Copyright notice
 *
 * (c) 2018
 * Frederic von Vlahovits fvv@posteo.de
 *
 * Licensed under The MIT License (MIT)
 */

class CartStorageService
{

    /**
     * @param integer $item
     *
     * @throws \Exception
     */

    public static function add(int $item)
    {
        $_SESSION['cart'][] = $item;
    }

    /**
     * @param integer $item
     *
     * @throws \Exception
     */

    public static function delete(int $item)
    {
        if (in_array($item, $_SESSION['cart'])) {
            $key = array_search($item, $_SESSION['cart']);
            unset($_SESSION['cart'][$key]);
        }
    }

    /**
     * @return int
     *
     * @throws \Exception
     */

    public static function count()
    {
        return count($_SESSION['cart']);
    }

    /**
     * @return array $basket
     *
     * @throws \Exception
     */

    public static function get()
    {
        $basket = [];

        foreach ($_SESSION['cart'] as $item) {
            if ((int)$item) {

                $pdoService = new PDOService();
                $dish = $pdoService->getDish($item);

                if (array_key_exists($dish['id'], $basket)) {
                    $basket[$dish['id']]['quantity']++;
                    $basket[$dish['id']]['price'] += (float) $dish['price'];
                } elseif (!array_key_exists($dish['id'], $basket)) {
                    $basket[$dish['id']]['name'] = $dish['name'];
                    $basket[$dish['id']]['description'] = $dish['description'];
                    $basket[$dish['id']]['price'] = (float) $dish['price'];
                    $basket[$dish['id']]['quantity'] = 1;
                }

                $basket['sum'] += (float) $dish['price'];

            }
        }

        return $basket;

    }

    /**
     * @throws \Exception
     */

    public static function reset()
    {
        $_SESSION['cart'] = [];
    }

}