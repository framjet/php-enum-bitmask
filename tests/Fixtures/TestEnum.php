<?php

declare(strict_types=1);

namespace FramJet\Packages\EnumBitmaskTest\Fixtures;

use FramJet\Packages\EnumBitmask\Attributes\MultipleBits;
use FramJet\Packages\EnumBitmask\BitmaskFunctionality;
use Stringable;

enum TestEnum: int
{
    use BitmaskFunctionality;

    case Flag1  = 0b0000_0000_0000_0000_0000_0000_0000_0001;
    case Flag2  = 0b0000_0000_0000_0000_0000_0000_0001_0000;
    case Flag3  = 0b0000_0000_0000_0000_0000_0001_0000_0000;
    case Flag4  = 0b0000_0000_0000_0000_0001_0000_0000_0000;
    case Flag5  = 0b0000_0000_0000_0001_0000_0000_0000_0000;
    case Flag6  = 0b0000_0000_0001_0000_0000_0000_0000_0000;
    case Flag7  = 0b0000_0001_0000_0000_0000_0000_0000_0000;
    case Flag8  = 0b0001_0000_0000_0000_0000_0000_0000_0000;
    #[MultipleBits]
    case Flag10 = 0b0001_0001_0001_0001_0001_0001_0001_0001;
}
