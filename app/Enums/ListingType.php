<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum ListingType: string
{
    case Buy = 'buy';
    case Sell = 'sell';
}
