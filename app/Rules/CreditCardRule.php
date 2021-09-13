<?php

declare(strict_types=1);

namespace App\Rules;

use MarvinLabs\Luhn\Rules\LuhnRule;
use Illuminate\Contracts\Validation\Rule;

/**
 * Rule for credit cards
 *
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Rules
 */
class CreditCardRule extends LuhnRule
{
}
