<?php

declare(strict_types=1);

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use App\Models\System\Traits\HasFolders;
use App\Models\System\Traits\HasAttachments;
use App\Models\Shared\Traits\HasMetaFields;

/**
 * Messages Eloquent Model
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Models\System
 */
class Message extends Model implements Searchable
{
    use HasMetaFields,
        HasFolders,
        HasAttachments;

    /**
     * The database table used by the model.
     *
     * @var string $table
     * @access protected
     */
    protected $table = 'messages';

    /**
     * Message sent status
     *
     * @var string SENT
     */
    const SENT = 'sent';

    /**
     * Message read status
     *
     * @var string READ
     */
    const READ = 'read';

    /**
     * Message unread status
     *
     * @var string UNREAD
     */
    const UNREAD = 'unread';

    /**
     * Message deleted status
     *
     * @var string DELETED
     */
    const DELETED = 'deleted';

    /**
     * @var string QUEUE
     */
    const QUEUE = 'queue';

    /**
     * Status Types
     *
     * @var array STATUS_TYPES
     */
    const STATUS_TYPES = [
        self::QUEUE,
        self::SENT,
        self::READ,
        self::UNREAD,
        self::DELETED
    ];

    /**
     * Returns search sesults
     *
     * @return \Spatie\Searchable\SearchResult
     */
    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            ($this->first_name . ' ' . $this->last_name . ' (' . $this->email . ')'),
            null,
        );
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array Type casting field columns before interting to database.
     * @access protected
     */
    protected $casts = [
        'id'            => 'integer',
        'uuid'          => 'string',
        'recipient'     => 'integer',
        'type'          => 'string',
        'subject'       => 'string',
        'body'          => 'string',
        'meta_fields'   => 'object',
        'status'        => 'string',
        'sent_at'       => 'datetime',
        'read_at'       => 'datetime',
        'created_at'    => 'datetime',
    ];
}
