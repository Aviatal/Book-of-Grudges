<?php

namespace Tests\Unit;

use App\Support\SkillTestOutcome;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SkillTestOutcomeTest extends TestCase
{
    public static function fumbleRolls(): array
    {
        return [
            'próg (96) to jeszcze nie pech' => [96, false],
            '97 to już pech' => [97, true],
            '98' => [98, true],
            '99' => [99, true],
            '100' => [100, true],
        ];
    }

    #[DataProvider('fumbleRolls')]
    public function test_is_fumble(int $roll, bool $expected): void
    {
        $this->assertSame($expected, SkillTestOutcome::isFumble($roll));
    }

    public function test_levels_are_zero_below_a_full_ten_point_margin(): void
    {
        // Przykład z rozmowy z użytkownikiem: rzut 46 przy umiejętności 43 — różnica 3, brak poziomu.
        $this->assertSame(0, SkillTestOutcome::levels(46, 43));
        $this->assertSame(0, SkillTestOutcome::levels(43, 43));
    }

    public function test_levels_count_full_ten_point_margins_regardless_of_direction(): void
    {
        // Przykład z rozmowy z użytkownikiem: rzut 56 przy umiejętności 43 — różnica 13 -> 1 poziom.
        $this->assertSame(1, SkillTestOutcome::levels(56, 43));
        // Margines w drugą stronę (duży sukces) liczy się tak samo.
        $this->assertSame(3, SkillTestOutcome::levels(10, 43));
        $this->assertSame(2, SkillTestOutcome::levels(99, 79));
    }
}
