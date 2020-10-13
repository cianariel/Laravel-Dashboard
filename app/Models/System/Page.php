<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use \Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Shared\Traits\HasMetaFields;

/**
 * Pages Eloquent Model
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Models\System
 */
class Page extends Model implements HasMedia
{
    use HasMetaFields,
        InteractsWithMedia,
        RevisionableTrait,
        SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     * @access protected
     */
    protected $table = 'pages';

    /**
     * @var string TEMPLATE_DEFAULT
     */
    const TEMPLATE_DEFAULT = 'default';

    /**
     * @var string TEMPLATE_BLANK
     */
    const TEMPLATE_BLANK = 'blank';

    /**
     * Page templates
     *
     * @var string TEMPLATES
     */
    const TEMPLATES = [
        self::TEMPLATE_DEFAULT,
        self::TEMPLATE_BLANK
    ];

    /**
     * Page active status
     *
     * @var string ACTIVE
     */
    const ACTIVE = 'active';

    /**
     * Page inactive status
     *
     * @var string INACTIVE
     */
    const INACTIVE = 'inactive';

    /**
     * Status Types
     *
     * @var array STATUS_TYPES
     */
    const STATUS_TYPES = [
        self::ACTIVE,
        self::INACTIVE
    ];

    /**
     * Page visible
     *
     * @var string VISIBLE
     */
    const VISIBLE = 'true';

    /**
     * Page hidden
     *
     * @var string HIDDEN
     */
    const HIDDEN = 'false';

    /**
     * Page visibility types
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
     * Resized Images
     *
     * @param Media $media
     * @return void
     * @access public
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
              ->width(150)
              ->height(150)
              ->sharpen(10)
              ->keepOriginalImageFormat()
              ->nonQueued();

        $this->addMediaConversion('large')
              ->width(800)
              ->height(800)
              ->sharpen(10)
              ->keepOriginalImageFormat()
              ->nonQueued();
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array $casts Type casting field columns before interting to database.
     * @access protected
     */
    protected $casts = [
        'id'                => 'id',
        'uuid'              => 'string',
        'user_id'           => 'integer',
        'name'              => 'string',
        'title'             => 'string',
        'slug'              => 'string',
        'contnet'           => 'string',
        'excerpt'           => 'string',
        'seo_title'         => 'string',
        'meta_keywords'     => 'string',
        'meta_description'  => 'string',
        'meta_robots'       => 'string',
        'meta_fields'   => 'object',
        'status'            => 'string',
        'visible'           => 'string',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime'
    ];
}
