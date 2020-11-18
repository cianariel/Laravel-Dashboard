<?php

declare(strict_types=1);

namespace App\Models\System;

use App\Models\Shared\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * CalendarEvent Eloquent Model
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Models\System
 */
class CalendarEvent extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var    string
     * @access protected
     */
    protected $table = 'calendar_events';

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
        'title',
        'meta_fields',
        'status',
        'start_at',
        'ends_at'
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
        'id'            => 'integer',
        'uuid'          => 'string',
        'title'         => 'string',
        'meta_fields'   => 'object',
        'start_at'      => 'datetime',
        'ends_at'       => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime'
    ];
}
