<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Rule for while spacing checking
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 GeekBidz, LLC
 * @package  App\Rules
 */
class WhiteSpaceRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     **/
    public function passes($attribute, $value) : bool
    {
        return preg_match('/\s/', $value) === 0;
    }

    /**
     * Get the validation error message.
     *
     **/
    public function message() : string
    {
        return 'The :attribute must be an unbroken string of text, it cannot include spaces';
    }
}
