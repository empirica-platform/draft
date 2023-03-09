<?php

namespace EmpiricaPlatform\Terminal\ValueObject;

use EmpiricaPlatform\Contracts\SymbolEnum;

enum Symbol: string implements SymbolEnum
{
    case BTC = 'BTC';
    case USDT = 'USDT';
}

