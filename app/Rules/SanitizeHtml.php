<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Rule for removing html tags
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 GeekBidz, LLC
 * @package  App\Rules
 */
class SanitizeHtml implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (clean($value, $attribute) == $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.sanitizehtml');
    }
}
