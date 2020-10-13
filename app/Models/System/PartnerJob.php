<?php

declare(strict_types=1);

namespace App\Models\System;

use Exception;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shared\Traits\HasMetaFields;

/**
 * PartnerJob Eloquent Model
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Models\System
 */
class PartnerJob extends Model
{
    use HasMetaFields;

    /**
     * The database table used by the model.
     *
     * @var string
     * @access protected
     */
    protected $table = 'partner_jobs';

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
        'type',
        'name',
        'industry',
        'assigned_user',
        'city',
        'state',
        'country',
        'remote_job',
        'currency',
        'salary_type',
        'salary',
        'propation_period',
        'reason_closed',
        'meta_fields',
        'status',
        'start_at',
        'assigned_at',
        'expired_at',
        'closed_at'
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
        'id'                => 'integer',
        'uuid'              => 'string',
        'type'              => 'type',
        'assigned_user'     => 'integer',
        'slug'              => 'string',
        'name'              => 'string',
        'label'             => 'string',
        'industry'          => 'string',
        'job_keyword'       => 'string',
        'city'              => 'string',
        'state'             => 'string',
        'country'           => 'string',
        'remote_job'        => 'string',
        'currency'          => 'string',
        'reason_closed'     => 'string',
        'status'            => 'status',
        'salary'            => 'integer',
        'propation_period'  => 'integer',
        'geek_connects'     => 'integer',
        'meta_fields'       => 'object',
        'start_at'          => 'datetime',
        'expired_at'        => 'datetime',
        'assigned_at'       => 'datetime',
        'closed_at'         => 'datetime'
    ];
}
