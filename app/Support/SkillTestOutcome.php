<?php

namespace App\Support;

/**
 * Drobne, czysto funkcyjne wyliczenia dotyczące pojedynczego rzutu k100 na cechę/umiejętność —
 * wydzielone z ChatService, żeby dało się je przetestować bez losowości i bez HTTP.
 */
class SkillTestOutcome
{
    private const FUMBLE_THRESHOLD = 97;

    private const POINTS_PER_LEVEL = 10;

    /**
     * Pech — rzut 97, 98, 99 lub 100. Niezależnie od tego, czy test formalnie wyszedł.
     */
    public static function isFumble(int $roll): bool
    {
        return $roll >= self::FUMBLE_THRESHOLD;
    }

    /**
     * O ile pełnych poziomów (10 punktów) rzut różni się od progu — 0, gdy różnica jest
     * mniejsza niż 10 (wtedy nie ma czego pokazywać jako dodatkowej informacji o poziomie).
     */
    public static function levels(int $roll, int $effectiveValue): int
    {
        return intdiv(abs($roll - $effectiveValue), self::POINTS_PER_LEVEL);
    }
}
