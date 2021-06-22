<?php


namespace App\Model\Exception;
use Exception;


class UserNotFound extends Exception
{
    public static function throwException()
    {
        throw new self('User not found');
    }
}