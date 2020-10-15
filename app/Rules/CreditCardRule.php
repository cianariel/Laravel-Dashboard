<?php

declare(strict_types=1);

namespace App\Rules;

use \MarvinLabs\Luhn\Rules\LuhnRule;
use Illuminate\Contracts\Validation\Rule;

/**
 * Rule for credit cards
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 GeekBidz, LLC
 * @package  App\Rules
 */
class CreditCardRule extends LuhnRule
{
}
