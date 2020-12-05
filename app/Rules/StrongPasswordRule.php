<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Rule for checking for strong passwords
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Rules
 */
class StrongPasswordRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * The password must be 8 - 30 characters in length,
     * and include a number, a symbol, an upper case letter,
     * and a lower case letter.
     **/
    public function passes($attribute, $value): bool
    {
        return preg_match(
            '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@()$%^&*=_{}[\]:;"\'|\\<>,.\/~`±§+-]).{8,30}$/',
            $value
        ) > 0;
    }

    /**
     * Get the validation error message.
     **/
    public function message(): string
    {
        return 'The :attribute must be an unbroken string of text, it cannot include spaces';
    }
}
