<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CannotDeleteUserException extends Exception
{
    public static function self(): self
    {
        return new self('Nie możesz usunąć własnego konta.', Response::HTTP_FORBIDDEN);
    }

    public static function superadmin(): self
    {
        return new self('Nie można usunąć superadmina — najpierw odbierz mu tę rolę w edycji.', Response::HTTP_CONFLICT);
    }

    /** Usunięcie właściciela skasowałoby kaskadowo całą kampanię wraz z graczami, bohaterami i czatem. */
    public static function ownsCampaigns(): self
    {
        return new self('Użytkownik jest właścicielem kampanii — usunięcie skasowałoby ją razem z jej graczami i bohaterami.', Response::HTTP_CONFLICT);
    }
}
