<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DiscountScope: string implements HasLabel
{
    case Product = 'product';
    case Service = 'service';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
