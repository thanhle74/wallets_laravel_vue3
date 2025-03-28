<?php
declare(strict_types=1);

namespace Modules\Support\Enums;

enum Status: int
{
    case ACTIVE = 1;
    case DISABLED = 2;
}
