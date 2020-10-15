<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Rule for title case checking
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package  App\Rules
 */
class TitleCaseRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     **/
    public function passes($attribute, $value) : bool
    {
        return ucwords($value) === $value;
    }

    /**
     * Get the validation error message.
     *
     **/
    public function message() : string
    {
        return 'Each word in :attribute must begin with a capital letter';
    }
}
