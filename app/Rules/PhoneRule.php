<?php

declare(strict_types=1);

namespace App\Rules;

use LVR\Phone\Phone as LVRPhone;
use Illuminate\Contracts\Validation\Rule;

/**
 * Phone Validation Rule
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 GeekBidz, LLC
 * @package App\Rules
 */
class PhoneRule extends LVRPhone
{
}
