<?php

declare(strict_types=1);

namespace App\Models\System\Traits;

use App\Models\System\Message;

/**
 * Has Messages Relations Trait
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Models\System\Traits
 */
trait HasMessages
{
    /**
     * Gets messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @access public
     */
    public function messages()
    {
        return $this->belongsToMany(Message::class);
    }

    /**
     * Has Messages
     *
     * @return bool
     * @access public
     */
    public function hasMessages():bool
    {
        return ($this->messages->count() !== 0)? true : false;
    }

    /**
     * Determines if has the given message
     *
     * @param  App\Models\System\Message|int $message
     * @return bool
     * @access public
     */
    public function hasMessage($message): bool
    {
        if (filled($message)) {
            if (is_numeric($message) && is_finite(intval($message))) {
                return $this->messages()->where('id', intval($message))->exists();
            }

            if ($message instanceof Message) {
                return $this->messages()->where('id', $message->id)->exists();
            }
        }

        return false;
    }

    /**
     * Assign the given message
     *
     * @param  App\Models\System\Message|int $message
     * @return bool
     * @access public
     */
    public function assignMessage($message): bool
    {
        if (!$this->hasMessage($message)) {
            if (is_numeric($message) && is_finite(intval($message))) {
                $message = Message::where('id', intval($message))
                    ->select(['id'])
                    ->firstOrFail();
            }

            if ($message instanceof Message) {
                return $this->messages()->saveOrFail($message);
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * Unassign the given message
     *
     * @param  App\Models\System\Message|int $message
     * @return bool
     * @access public
     */
    public function unassignMessage($message): bool
    {
        if ($this->hasMessage($message)) {
            if (is_numeric($message) && is_finite(intval($message))) {
                return ($this->messages()->detach(intval($message)))? true : false;
            }

            if ($message instanceof Message) {
                return ($this->messages()->detach($message->id))? true : false;
            }
        }

        return false;
    }
}
