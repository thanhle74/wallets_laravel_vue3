<?php
declare(strict_types=1);

namespace ThanhAloha\Support\Enums;

enum Type: int
{
    case BANK = 1;
    case CASH = 2;
    case CRYPTO = 3;
}
