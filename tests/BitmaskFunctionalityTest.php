<?php

declare(strict_types=1);

namespace FramJet\Packages\EnumBitmaskTest;

use FramJet\Packages\EnumBitmaskTest\Fixtures\TestEnum;
use PHPUnit\Framework\TestCase;

class BitmaskFunctionalityTest extends TestCase
{
    public function testEnumBuildFromCases(): void
    {
        self::assertSame(
            0b0000_0000_0000_0000_0000_0001_0001_0001,
            TestEnum::build(
                TestEnum::Flag1,
                TestEnum::Flag2,
                TestEnum::Flag3
            )
        );

        self::assertSame(
            0,
            TestEnum::build()
        );
    }

    public function testEnumBitMaskMaskCreation(): void
    {
        self::assertSame(
            0b0000_0000_0000_0000_0000_0001_0001_0001,
            TestEnum::mask(
                TestEnum::Flag1,
                TestEnum::Flag2,
                TestEnum::Flag3
            )
        );

        self::assertSame(
            0,
            TestEnum::mask()
        );
    }

    public function testParseValueToCases(): void
    {
        self::assertSame(
            [
                TestEnum::Flag1,
                TestEnum::Flag2,
                TestEnum::Flag3,
            ],
            TestEnum::parse(0b0000_0000_0000_0000_0000_0001_0001_0001)
        );
    }

    public function testParseValueToCasesWithMissingCases(): void
    {
        self::assertSame(
            [
                TestEnum::Flag1,
                TestEnum::Flag2,
                TestEnum::Flag3,
            ],
            TestEnum::parse(0b0000_0000_0000_0000_0000_0001_0001_1111)
        );
    }

    public function testValueToString(): void
    {
        self::assertSame(
            '0b0000_0000_0000_0000_0000_0001_0001_0001',
            TestEnum::valueToString(
                TestEnum::build(
                    TestEnum::Flag1,
                    TestEnum::Flag2,
                    TestEnum::Flag3
                )
            )
        );
    }

    public function testEnumCaseToString(): void
    {
        self::assertSame(
            '0b0000_0000_0000_0000_0000_0000_0000_0001',
            TestEnum::Flag1->toString(),
        );
    }

    public function testEnumHasCaseFromBitmask(): void
    {
        self::assertTrue(
            TestEnum::hasCase(0b0000_0000_0000_0000_0000_0001_0000_0000)
        );

        self::assertFalse(
            TestEnum::hasCase(0b0000_0000_0000_0000_0000_0000_0000_1111)
        );
    }

    public function testEnumBitmaskSet(): void
    {
        $value = TestEnum::set(
            0b0000_0000_0000_0000_0000_0000_0000_1111,
            TestEnum::Flag8,
        );

        self::assertSame(
            0b0001_0000_0000_0000_0000_0000_0000_1111,
            $value
        );

        $value = TestEnum::set(
            0b0000_0000_0000_0000_0000_0000_0000_1111,
            TestEnum::Flag8,
            TestEnum::Flag7,
            TestEnum::Flag6
        );

        self::assertSame(
            0b0001_0001_0001_0000_0000_0000_0000_1111,
            $value
        );
    }

    public function testEnumBitmaskClear(): void
    {
        $value = TestEnum::clear(
            0b0001_0000_0000_0000_0000_0000_0000_1111,
            TestEnum::Flag8,
        );

        self::assertSame(
            0b0000_0000_0000_0000_0000_0000_0000_1111,
            $value
        );

        $value = TestEnum::clear(
            0b0001_0001_0001_0000_0000_0000_0000_1111,
            TestEnum::Flag8,
            TestEnum::Flag7,
            TestEnum::Flag6
        );

        self::assertSame(
            0b0000_0000_0000_0000_0000_0000_0000_1111,
            $value
        );
    }

    public function testEnumBitmaskToggle(): void
    {
        $value = TestEnum::toggle(
            0b0000_0000_0000_0000_0000_0000_0000_0000,
            TestEnum::Flag8,
        );

        self::assertSame(
            0b0001_0000_0000_0000_0000_0000_0000_0000,
            $value
        );

        $value = TestEnum::toggle(
            $value,
            TestEnum::Flag8,
        );

        self::assertSame(
            0b0000_0000_0000_0000_0000_0000_0000_0000,
            $value
        );

        $value = TestEnum::toggle(
            0b0000_0000_0000_0000_0000_0000_0000_0000,
            TestEnum::Flag8,
            TestEnum::Flag7,
            TestEnum::Flag6
        );

        self::assertSame(
            0b0001_0001_0001_0000_0000_0000_0000_0000,
            $value
        );

        $value = TestEnum::toggle(
            $value,
            TestEnum::Flag7,
            TestEnum::Flag6
        );

        self::assertSame(
            0b0001_0000_0000_0000_0000_0000_0000_0000,
            $value
        );
    }

    public function testEnumBitMaskIsOnOff(): void
    {
        $value = 0b0001_0000_0000_0000_0000_0000_0000_0000;

        self::assertTrue(TestEnum::on($value, TestEnum::Flag8));
        self::assertFalse(TestEnum::on($value, TestEnum::Flag7));

        self::assertFalse(TestEnum::off($value, TestEnum::Flag8));
        self::assertTrue(TestEnum::off($value, TestEnum::Flag7));
    }

    public function testEnumBitMaskIsAnyAllNone(): void
    {
        $value = 0b0000_0000_0000_0000_0000_0000_0011_0011;

        self::assertTrue(TestEnum::any($value, TestEnum::Flag1, TestEnum::Flag3, TestEnum::Flag4));
        self::assertFalse(TestEnum::any($value, TestEnum::Flag3, TestEnum::Flag4));
        self::assertFalse(TestEnum::any($value, TestEnum::Flag5));
        self::assertTrue(TestEnum::any($value));

        self::assertTrue(TestEnum::all($value, TestEnum::Flag1, TestEnum::Flag2));
        self::assertFalse(TestEnum::all($value, TestEnum::Flag1, TestEnum::Flag2, TestEnum::Flag4));
        self::assertFalse(TestEnum::all($value, TestEnum::Flag3, TestEnum::Flag4));
        self::assertFalse(TestEnum::all($value, TestEnum::Flag5));
        self::assertTrue(TestEnum::all($value));

        self::assertFalse(TestEnum::none($value, TestEnum::Flag1, TestEnum::Flag2));
        self::assertFalse(TestEnum::none($value, TestEnum::Flag1, TestEnum::Flag2, TestEnum::Flag4));
        self::assertTrue(TestEnum::none($value, TestEnum::Flag3, TestEnum::Flag4));
        self::assertTrue(TestEnum::none($value, TestEnum::Flag5));
        self::assertTrue(TestEnum::none($value));
    }
}
