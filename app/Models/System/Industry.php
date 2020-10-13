<?php

declare(strict_types=1);

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Exception;

/**
 * Industry Eloquent Model
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Models\System
 */
class Industry extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     * @access protected
     */
    protected $table = 'industries';

    /**
     * @var string ACTIVE
     */
    const ACTIVE = 'active';

    /**
     * @var string INACTIVE
     */
    const INACTIVE = 'inactive';

    /**
     * @var array STATUS_TYPES
     */
    const STATUS_TYPES = [
        self::INACTIVE,
        self::ACTIVE
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'label',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array $casts Type casting field columns before interting to database.
     */
    protected $casts = [
        'id'        => 'integer',
        'name'      => 'string',
        'label'     => 'string'
        'status'    => 'string'
    ];
}
