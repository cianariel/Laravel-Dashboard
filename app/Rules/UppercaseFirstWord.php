<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * UppercaseFirstWord Validation Rule
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package App\Rules
 */
class UppercaseFirstWord implements Rule
{
       /**
     * Determine if the validation rule passes.
     *
     **/
public function passes($attribute, $value) : bool
{
    return mb_strtolower($value) === $value;
}

    /**
     * Get the validation error message.
     *
     **/
public function message() : string
{
    return 'The :attribute first letter must be capitalized';
}
