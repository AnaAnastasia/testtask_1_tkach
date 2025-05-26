<?php

namespace App\Services;

use App\Entities\Rule;

class RuleEvaluator
{
    private array $hotelData;

    public function __construct(array $hotelData)
    {
        $this->hotelData = $hotelData;
    }

    public function evaluate(Rule $rule): bool
    {
        foreach ($rule->conditions as $condition) {
            $actual = $this->hotelData[$condition->field] ?? null;
            if (!$this->compare($actual, $condition->operator, $condition->value)) {
                return false;
            }
        }

        return true;
    }

    private function compare(mixed $actual, string $operator, mixed $expected): bool
    {
        return match ($operator) {
            '='  => $actual == $expected,
            '!=' => $actual != $expected,
            '>'  => $actual > $expected,
            '<'  => $actual < $expected,
            default => false,
        };
    }
}