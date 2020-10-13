<?php

declare(strict_types=1);

namespace App\Models\System\Traits;

use App\Models\System\Industry;

/**
 * Has Industry Relation Trait
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Models\System\Traits
 */
trait HasIndustries
{
    /**
     * Gets industries
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @access public
     */
    public function industries()
    {
        return $this->belongsToMany(Industry::class);
    }

    /**
     * Checks if has any industry
     *
     * @return bool
     * @access public
     */
    public function hasIndustries(): bool
    {
        return ($this->industries->count() !== 0) ? true : false;
    }

    /**
     * Determines if has the given industry
     *
     * @param App\Models\System\Industry|int|string $industry
     *
     * @return bool
     * @access public
     */
    public function hasIndustry($industry): bool
    {
        if (filled($industry)) {
            if (is_numeric($industry) && is_finite(intval($industry))) {
                return $this->industries()->where('id', intval($industry))->exists();
            }

            if (is_string($industry)) {
                return $this->industries()->where('name', $industry)->exists();
            }

            if ($industry instanceof Industry) {
                return $this->industries()->where('id', $industry->id)->exists();
            } else {
                return false;
            }
        }

        return false;
    }

    /**
     * Assign the given industry
     *
     * @param App\Models\System\Industry|int|string $industry
     *
     * @return bool
     * @access public
     */
    public function assignIndustry($industry): bool
    {
        if (!$this->hasIndustry($industry)) {
            if (is_numeric($industry) && is_finite(intval($industry))) {
                $industry = Industry::where('id', intval($industry))
                               ->select(['id'])
                               ->findOrFail();
            } elseif (is_string($industry)) {
                $industry = Industry::where('name', $industry)
                               ->select(['id'])
                               ->firstOrFail();
            }

            if ($industry instanceof Industry) {
                return ($this->industries()->save($industry))? true : false;
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * Assign the given industry
     *
     * @param App\Models\System\Industry|int|string $industry
     *
     * @return bool
     * @access public
     */
    public function unassignIndustry($industry): bool
    {
        if ($this->hasIndustry($industry)) {
            if (is_numeric($industry) && is_finite(intval($industry))) {
                $industry = $this->industries()->where('id', intval($industry))
                               ->select(['id'])
                               ->findOrFail();
            } elseif (is_string($industry)) {
                $industry = $this->industries()->where('name', $industry)
                               ->select(['id'])
                               ->firstOrFail();
            }

            if ($industry instanceof Industry) {
                return ($this->industries()->detach($industry->id))? true : false;
            }
        }

        return false;
    }
}
