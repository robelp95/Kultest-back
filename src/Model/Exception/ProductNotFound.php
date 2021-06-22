<?php


namespace App\Model\Exception;

use Exception;


class ProductNotFound extends Exception
{
    public static function throwException()
    {
        throw new self('Product not found');
    }
}