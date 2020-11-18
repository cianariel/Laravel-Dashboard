<?php

declare(strict_types=1);

namespace App\Http\Controllers\WebHook;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Exception;

/**
 * WebHooksController
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\WebHook
 */
class WebHooksController extends CashierController
{
}
