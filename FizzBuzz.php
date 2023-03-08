<?php

namespace FizzBuzz\Core;

interface ReplaceRuleInterface
{
    public function match(string $carry, int $n): bool;
    public function apply(string $carry, int $n):string;
}

class NumberConverter
{
    /**
     * @param ReplaceRuleInterface[] $rules
     */
    public function __construct(
        protected array $rules
    ) {}

    public function convert(int $n): string
    {
        $carry = '';
        foreach ($this->rules as $rule) {
            if ($rule->match($carry, $n)) {
                $carry = $rule->apply($carry, $n);
            }
        }
        return $carry;
    }
}

namespace FizzBuzz\Spec;

use FizzBuzz\Core\ReplaceRuleInterface;
use FizzBuzz\Core\NumberConverter;

/**
 * 倍数に関するルール
 */
class CyclicNumberRule implements ReplaceRuleInterface
{
    public function __construct(
        protected int $base,
        protected string $replacement
    ) {}

    public function match(string $carry, int $n): bool
    {
        return $n % $this->base == 0;
    }

    public function apply(string $carry, int $n):string
    {
        return $carry . $this->replacement;
    }
}

/**
 * 変換条件に該当しない場合のルール
 */
class PassThroughRule implements ReplaceRuleInterface
{
    public function match(string $carry, int $n): bool
    {
        return $carry == '';
    }

    public function apply(string $carry, int $n): string
    {
        return (string)$n;
    }
}

$fizzBuzz = new NumberConverter([
    new CyclicNumberRule(3, "Fizz"),
    new CyclicNumberRule(5, "Bizz"),
    new PassThroughRule(),
]);

echo $fizzBuzz->convert(1), "\n";
echo $fizzBuzz->convert(3), "\n";
echo $fizzBuzz->convert(5), "\n";
echo $fizzBuzz->convert(15), "\n";