<?php

declare(strict_types=1);

namespace App\Models\System;

use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Roles Eloquent Model
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Models\System
 */
class Role extends SpatieRole
{
    /**
     * The database table used by the model.
     *
     * @var string
     * @access protected
     */
    protected $table = 'roles';

    /**
     * Status Active
     *
     * @var string ACTIVE
     */
    const ACTIVE = 'active';

    /**
     * Status Inactive
     * @var string INACTIVE
     */
    const INACTIVE = 'inactive';


    /**
     * Role super admin users
     *
     * @var string SUPER_ADMIN
     */
    const SUPER_ADMIN = 'super_admin';

    /**
     * Role admin users
     *
     * @var string ADMIN
     */
    const ADMIN = 'admin';

    /**
     * Role for generic users
     *
     * @var string USER
     */
    const USER = 'user';

    /**
     * Role for API users
     *
     * @var string API
     */
    const API = 'api';

    /**
     * Role for UNASSIGNED users
     * @var string UNASSIGNED
     */
    const UNASSIGNED = 'unassigned';

    /**
     * List of ROLES
     *
     * @var array roles
     */
    const ROLES = [
        self::API,
        self::UNASSIGNED,
        self::SUPER_ADMIN,
        self::ADMIN,
        self::USER
    ];

    /**
     * Role status types
     *
     * @var array STATUS_TYPES
     */
    const STATUS_TYPES = [
        self::ACTIVE,
        self::INACTIVE
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array $casts Type casting field columns before interting to database.
     * @access protected
     */
    protected $casts = [
        'name'          => 'string',
        'guard_name'    => 'string',
        'label'         => 'string',
        'status'        => 'string',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime'
    ];
}
