<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum BudgetType: string implements HasLabel
{
    case Reset = 'reset';
    case Rollover = 'rollover';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Reset => 'Reset',
            self::Rollover => 'Rollover',
        };
    }
}
