<?php

declare(strict_types=1);

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\System\Traits\HasBlogCategories;
use App\Models\System\Traits\HasSettings;
use App\Models\System\Traits\HasPosts;
use App\Models\System\Traits\HasTags;

/**
 * Blogs Eloquent Model
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Models\System
 */
class Blog extends Model
{
    use HasSettings,
        HasPosts,
        HasTags,
        SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     * @access protected
     */
    protected $table = 'blogs';

    /**
     * Blog status active
     *
     * @var string ACTIVE
     */
    const ACTIVE = 'active';

    /**
     * Blog status inactive
     *
     * @var string INACTIVE
     */
    const INACTIVE = 'inactive';

    /**
     * Status types
     *
     * @var array STATUS_TYPES
     */
    const STATUS_TYPES = [
        self::ACTIVE,
        self::INACTIVE
    ];

    /**
     *  Blog visible
     *
     * @var string VISIBLE
     */
    const VISIBLE = 'true';

    /**
     * @var string HIDDEN
     */
    const HIDDEN = 'false';

    /**
     * Visible types
     *
     * @var array VISIBLE_TYPES
     */
    const VISIBLE_TYPES = [
        self::VISIBLE,
        self::HIDDEN
    ];

    /**
     * Meta Robots Options
     *
     * @var array META_ROBOTS
     */
    const META_ROBOTS = [
        'NONE',
        'NOINDEX',
        'NOFOLLOW',
        'NOCACHE',
        'NOSNIPPET',
        'NOIMAGEINDEX',
        'INDEX',
        'FOLLOW',
        'SNIPPET',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array $casts Type casting field columns before interting to database.
     * @access protected
     */
    protected $casts = [
        'id'            => 'integer',
        'slug'          => 'string',
        'name'          => 'string',
        'title'         => 'string',
        'status'        => 'string',
        'visible'       => 'string',
        'meta_fields'   => 'meta_fields',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime'
    ];
}
