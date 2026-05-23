<?php

namespace App\Exceptions;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class HeroNotFoundException extends RuntimeException
{
    public function __construct(string $message = 'Hero not found for this user.')
    {
        parent::__construct($message, Response::HTTP_NOT_FOUND);
    }
}
