<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model as BaseModel;
use App\Contracts\Metable;
use App\Models\Shared\Traits\HasMetaFields;

/**
 * Base Model
 *
 * @copyright 2020 MedRepTime, LLC
 * @package   App\Models\Shared
 */
class Model extends BaseModel implements Metable
{
    use HasMetaFields;
}
