<?php

namespace App\Models\Shared;

use Illuminate\Foundation\Auth\User as Model;
use App\Contracts\Metable;
use App\Models\Shared\Traits\HasMetaFields;

/**
 * Authenticatable
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDReptTime, LLC
 * @package   App\Models\Shared
 */
class Authenticatable extends Model implements Metable
{
    use HasMetaFields;
}