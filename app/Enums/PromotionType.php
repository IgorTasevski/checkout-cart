<?php

namespace App\Enums;

use App\Traits\EnumValuesTrait;

enum PromotionType: string
{
    use EnumValuesTrait;
    case MULTIPRICED = 'multipriced';
    case BUY_N_GET_ONE_FREE = 'buy_n_get_1_free';
    case MEAL_DEAL = 'meal_deal';
}
