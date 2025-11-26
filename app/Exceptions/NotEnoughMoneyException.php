<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class NotEnoughMoneyException extends Exception
{
    private const array MESSAGES = [
        "Kupiec zważył Twoją sakiewkę w dłoni i parsknął śmiechem. 'Za mało, przyjacielu. Tu się płaci złotem, nie dobrymi chęciami'.",
        "Przeliczyłeś monety trzy razy, ale matematyka jest bezlitosna. Twoja sakiewka jest lżejsza niż piórko gryfa. Nie stać Cię na to.",
        "'To nie przytułek dla ubogich' – warknął sprzedawca, widząc ile wykładasz na ladę. Brakuje Ci funduszy.",
        "W dnie twojej sakiewki widać dziurę. Brakuje monet, by sfinalizować tę transakcję. Wróć, gdy się odkujesz.",
        "Szukasz po kieszeniach, ale znajdujesz tylko kłęby kurzu i kilka miedziaków. To za mało. Musisz obejść się smakiem.",
        "Sprzedawca spojrzał na Twoje monety z politowaniem i zabrał towar z lady. 'Wróć, jak obrabujesz kogoś bogatszego'.",
    ];

    public function __construct()
    {
        $message = self::MESSAGES[array_rand(self::MESSAGES)];

        parent::__construct($message, Response::HTTP_PAYMENT_REQUIRED);
    }
}
