<?php


namespace App\Model\Exception;
use Exception;

class PaykuException extends Exception
{
    public static function throwException()
    {
        throw new self('User not found');
    }

}