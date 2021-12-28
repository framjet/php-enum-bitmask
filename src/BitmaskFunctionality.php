<?php

declare(strict_types=1);

namespace FramJet\Packages\EnumBitmask;

use function array_filter;
use function array_reduce;
use function chunk_split;
use function count;
use function sprintf;
use function substr;

/** @psalm-type Int32BitMask = int<0, 2147483647> */
trait BitmaskFunctionality
{
    /** @return Int32BitMask */
    public static function mask(self ...$bits): int
    {
        if (count($bits) === 0) {
            return 0;
        }

        return array_reduce($bits, static fn (?int $sum, self $case) => ($sum ?? 0) | $case->value);
    }

    /** @param Int32BitMask $value */
    public static function hasCase(int $value): bool
    {
        return self::tryFrom($value) !== null;
    }

    /** @param Int32BitMask $value */
    public static function valueToString(int $value): string
    {
        return '0b' . substr(chunk_split(sprintf('%\'032b', $value), 4, '_'), 0, -1);
    }

    public function toString(): string
    {
        return self::valueToString($this->value);
    }

    /** @return Int32BitMask */
    public static function build(self ...$bits): int
    {
        return self::set(0, ...$bits);
    }

    /** @param Int32BitMask $value */

    /** @return Int32BitMask */
    public static function set(int $value, self ...$bits): int
    {
        return $value | self::mask(...$bits);
    }

    /** @param Int32BitMask $value */

    /** @return Int32BitMask */
    public static function clear(int $value, self ...$bits): int
    {
        return $value & ~self::mask(...$bits);
    }

    /** @param Int32BitMask $value */

    /** @return Int32BitMask */
    public static function toggle(int $value, self ...$bits): int
    {
        return $value ^ self::mask(...$bits);
    }

    /** @param Int32BitMask $value */
    public static function on(int $value, self $bit): bool
    {
        return ($value & $bit->value) === $bit->value;
    }

    /** @param Int32BitMask $value */
    public static function off(int $value, self $bit): bool
    {
        return ($value & $bit->value) === 0;
    }

    /** @param Int32BitMask $value */
    public static function any(int $value, self ...$bits): bool
    {
        if (count($bits) === 0) {
            return true;
        }

        return ($value & self::mask(...$bits)) > 0;
    }

    /** @param Int32BitMask $value */
    public static function all(int $value, self ...$bits): bool
    {
        if (count($bits) === 0) {
            return true;
        }

        $mask = self::mask(...$bits);

        return ($value & $mask) === $mask;
    }

    /** @param Int32BitMask $value */
    public static function none(int $value, self ...$bits): bool
    {
        if (count($bits) === 0) {
            return true;
        }

        $mask = self::mask(...$bits);

        return ($value & $mask) === 0;
    }

    /**
     * @param Int32BitMask $value
     *
     * @return list<static>
     */
    public static function parse(int $value): array
    {
        return array_filter(self::cases(), static fn (self $case) => self::on($value, $case));
    }
}
