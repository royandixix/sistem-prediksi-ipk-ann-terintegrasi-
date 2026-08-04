<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case OPERATOR = 'operator';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::OPERATOR => 'Operator',
        };
    }

    public function dashboardRoute(): string
    {
        return match ($this) {
            self::ADMIN => 'admin.dashboard',
            self::OPERATOR => 'operator.dashboard',
        };
    }
}