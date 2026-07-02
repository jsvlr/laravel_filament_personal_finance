<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Illuminate\Contracts\Support\Htmlable;
use Override;

enum TransactionType: string implements HasLabel, HasColor, HasIcon
{
    case Expense = 'expense';
    case Income  = 'income';

    #[Override]
    public function getLabel(): string|\Illuminate\Contracts\Support\Htmlable|null
    {
        return match ($this) {
            self::Expense => 'Expense',
            self::Income => 'Income',
        };
    }

    #[Override]
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Expense => 'danger',
            self::Income => 'success',
        };
    }

    #[Override]
    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Expense => 'heroicon-o-minus-circle',
            self::Income => 'heroicon-o-plus-circle',
        };
    }
}
