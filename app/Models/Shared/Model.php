<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model as BaseModel;
use App\Contracts\Metable;
use App\Models\Shared\Traits\HasMetaFields;

/**
 * Base Model
 *
 * @author      Antonio Vargas <localhost.80@gmail.com>
 * @copyright   2020 MedRepTime, LLC
 * @package     App\Models\Shared
 */
class Model extends BaseModel implements Metable
{
    use HasMetaFields;
}
