<?php

declare(strict_types=1);

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use App\Models\Shared\Traits\HasMetaFields;

/**
 * QuoteItem Eloquent Model
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Models\System
 */
class QuoteItem extends Model
{
    use HasMetaFields;

    /**
     * The database table used by the model.
     *
     * @var string
     * @access protected
     */
    protected $table = 'quote_items';

    /**
     * Disable timestamps
     *
     * @var bool timestamps
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array $casts Type casting field columns before interting to database.
     * @access protected
     */
    protected $casts = [
        'id'            => 'integer'
        'label'         => 'string',
        'description'   => 'string',
        'unit'          => 'string',
        'quantity'      => 'double',
        'price'         => 'integer',
        'meta_fields'          => 'object'
    ];
}
