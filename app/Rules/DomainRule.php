<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Rule for domain checking.
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 GeekBidz, LLC
 * @package  App\Rules
 */
class DomainRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     **/
    public function passes($attribute, $value) : bool
    {
        return preg_match('/^([\w-]+\.)*[\w\-]+\.\w{2,10}$/', $value) > 0;
    }

    /**
     * Get the validation error message.
     *
     **/
    public function message() : string
    {
        return 'The :attribute must be a valid domain without an http protocol e.g. google.com, www.google.com';
    }
}
