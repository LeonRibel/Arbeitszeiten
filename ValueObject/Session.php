<?php
namespace App\ValueObject;
class Session{
    public static function user(): int
    {
        return (int)$_SESSION["user"];
    }
}