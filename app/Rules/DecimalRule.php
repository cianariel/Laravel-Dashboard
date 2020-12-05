<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Rule for decimal checking
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Rules
 */
class DecimalRule implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * The rule has two parameters:
     * 1. The maximum number of digits before the decimal point.
     * 2. The maximum number of digits after the decimal point.
     **/
    public function passes($attribute, $value): bool
    {
        return preg_match(
            "/^[0-9]{1,{$this->parameters[0]}}(\.[0-9]{1,{$this->parameters[1]}})$/",
            $value
        ) > 0;
    }

    /**
     * Get the validation error message.
     **/
    public function message(): string
    {
        return 'The :attribute must be an appropriately formatted decimal';
    }
}
