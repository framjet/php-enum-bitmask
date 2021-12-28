# PHP Enum BitMask

[![Latest Stable Version][ico-stable-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-build]][link-build]
[![Total Downloads][ico-downloads]][link-packagist]
[![Dependents][ico-dependents]][link-dependents]
[![PHP Version Require][ico-php-versions]][link-packagist]
[![Mutation testing badge][ico-mutations]][link-mutations]
[![Type Coverage][ico-type-coverage]][link-type-coverage]

[![Email][ico-email]][link-email]

A small library that provides functionality to PHP 8.1 enums to act as BitMask flags.

## Why?

Sometimes you need some flags on the objects, to represent some features, most often its used simple property with the
type of
`bool` but then you start making several properties and then your object size (Serialized/JSON) starts growing quite a
lot.

The most efficient storage option for flags was always bitmask value. It provides up to 32 unique flags inside a single
`int32` value.

Another big benefit is the ability to make `AND`, `OR`, `NOT` operations in one call instead of doing many if
expressions to check all those property values.

## Install

Via [Composer][link-composer]

```shell
$ composer require framjet/enum-bitmask
```

## Usage

The library provides a trait [`BitmaskFunctionality`](src/BitmaskFunctionality.php) which is needed to include inside
int backed enum.

Here is a tiny example of using `Enum` to provide flags using space-efficient bitmask int value:

```php
<?php

use FramJet\Packages\EnumBitmask\BitmaskFunctionality;

enum Flag: int
{
    use BitmaskFunctionality;

    case Public = 0b000001;
    case Protected = 0b000010;
    case Private = 0b000100;
    case ReadOnly = 0b001000;
    case Static = 0b010000;
    case Final = 0b100000;
}

class Member
{
    public function __construct(private int $flags = 0)
    {
    }

    public function setPublic(): void
    {
        $this->flags = Flag::set(
            Flag::clear($this->flags, Flag::Private, Flag::Protected),
            Flag::Public
        );
    }

    public function isPublic(): bool
    {
        return Flag::on($this->flags, Flag::Public);
    }

    public function isReadOnly(): bool
    {
        return Flag::on($this->flags, Flag::ReadOnly);
    }

    /** @return list<Flag> */
    public function getFlags(): array
    {
        return Flag::parse($this->flags);
    }

    public function getFlagsValue(): int
    {
        return $this->flags;
    }
}

class Container
{
    /** @param list<Member> $members */
    public function __construct(private array $members = [])
    {
    }

    public function addMember(Member $member): void
    {
        $this->members[] = $member;
    }

    public function getMembers(Flag ...$flags): array
    {
        return array_filter($this->members, static fn(Member $m) => Flag::any($m->getFlagsValue(), ...$flags));
    }
}

$memberPublic = new Member();
$memberPublic->setPublic();

$memberPublic->getFlags(); // [Flag::Public]

$memberReadOnly = new Member(Flag::build(Flag::ReadOnly));

$memberReadOnly->isReadOnly(); // true
$memberReadOnly->isPublic();   // false

$memberPrivate = new Member(Flag::build(Flag::Private, Flag::ReadOnly));

$memberPrivate->isReadOnly(); // true
$memberPrivate->isPublic();   // false
$memberPrivate->getFlags();   // [Flag::Private, Flag::ReadOnly]

array_map(
    static fn(Flag $f) => $f->toString(),
    $memberPrivate->getFlags()
); // ['0b0000_0000_0000_0000_0000_0000_0000_0100', '0b0000_0000_0000_0000_0000_0000_0000_1000']

$container = new Container();
$container->addMember($memberPublic);
$container->addMember($memberReadOnly);
$container->addMember($memberPrivate);

$container->getMembers();                             // [$memberPublic, $memberReadOnly, $memberPrivate]
$container->getMembers(Flag::Public);                 // [$memberPublic]
$container->getMembers(Flag::ReadOnly);               // [$memberReadOnly, $memberPrivate]
$container->getMembers(Flag::ReadOnly, Flag::Public); // [$memberPublic, $memberReadOnly, $memberPrivate]
```

## License

Please see [License File](LICENSE) for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg

[ico-stable-version]: http://poser.pugx.org/framjet/enum-bitmask/v

[ico-build]: https://img.shields.io/github/workflow/status/framjet/php-enum-bitmask/Continuous%20Integration

[ico-quality]: https://img.shields.io/scrutinizer/quality/g/aurimasniekis/php-tdlib-schema

[ico-downloads]: http://poser.pugx.org/framjet/enum-bitmask/downloads

[ico-dependents]: http://poser.pugx.org/framjet/enum-bitmask/dependents

[ico-php-versions]: http://poser.pugx.org/framjet/enum-bitmask/require/php

[ico-mutations]: https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fframjet%2Fphp-enum-bitmask%2Fmain

[ico-type-coverage]: https://shepherd.dev/github/framjet/php-enum-bitmask/coverage.svg

[ico-email]: https://img.shields.io/badge/email-team@framjet.dev-blue.svg

[link-build]: https://github.com/framjet/php-enum-bitmask/actions

[link-packagist]: https://packagist.org/packages/framjet/enum-bitmask

[link-downloads]: https://packagist.org/packages/framjet/enum-bitmask/stats

[link-dependents]: https://packagist.org/packages/framjet/enum-bitmask/dependents?order_by=downloads

[link-mutations]: https://dashboard.stryker-mutator.io/reports/github.com/framjet/php-enum-bitmask/main

[link-type-coverage]: https://shepherd.dev/github/framjet/php-enum-bitmask

[link-email]: mailto:team@framjet.dev

[link-composer]: https://getcomposer.org/
