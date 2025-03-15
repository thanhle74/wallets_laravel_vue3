<?php
declare(strict_types=1);

namespace ThanhAloha\Support\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}
