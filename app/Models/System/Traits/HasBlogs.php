<?php

declare(strict_types=1);

namespace App\Models\System\Traits;

use App\Models\System\Blog;

/**
 * Has Blogs Relations Trait
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Models\System\Traits
 */
trait HasBlogs
{
    /**
     * Returns all blogs
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @access public
     */
    public function blogs()
    {
        return $this->belongsToMany(Blog::class);
    }

    /**
     * Determines if has any blogs
     *
     * @return bool
     * @access public
     */
    public function hasBlogs(): bool
    {
        return ($this->blogs()->count() !== 0)? true : false;
    }

    /**
     * Determines if has a given blog
     *
     * @param App\Models\System\Blog|int $blog
     *
     * @return bool
     * @access public
     */
    public function hasBlog($blog): bool
    {
        if (filled($blog)) {
            if (is_numeric($blog) && is_finite(intval($blog))) {
                return $this->blogs()->where('id', intval($blog))->exists();
            }

            if ($blog instanceof Blog) {
                return $this->blogs()->where('id', $blog->id)->exists();
            }
        }

        return false;
    }

    /**
     * Assign a given blog
     *
     * @param App\Models\System\Blog|int $blog
     *
     * @return bool
     * @access public
     */
    public function assignBlog($blog): bool
    {
        if (!$this->hasBlog($blog)) {
            if (is_numeric($blog) && is_finite(intval($blog))) {
                $blog = Blog::where('id', intval($blog))
                            ->select(['id'])
                            ->firstOrFail();
            }

            if ($blog instanceof Blog) {
                return ($this->blogs()->save($blog))? true : false;
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * Unassign a given blog
     *
     * @param App\Models\System\Blog|int $blog
     *
     * @return bool
     * @access public
     */
    public function unassignBlog($blog): bool
    {
        if ($this->hasBlog($blog)) {
            if (is_numeric($blog) && is_finite(intval($blog))) {
                return ($this->blogs()->detach(intval($blog)))? true : false;
            }

            if ($blog instanceof Blog) {
                return ($this->blogs()->detach($blog->id))? true : false;
            }
        }

        return true;
    }
}
