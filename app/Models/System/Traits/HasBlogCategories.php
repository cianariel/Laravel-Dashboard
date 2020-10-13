<?php

declare(strict_types=1);

namespace App\Models\System\Traits;

use App\Models\System\BlogCategory;

/**
 * Has Blog Categories Relations Trait
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Models\System\Traits
 */
trait HasBlogCategories
{
    /**
     * Returns all blog categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @access public
     */
    public function blogCategories()
    {
        return $this->belongsToMany(BlogCategory::class);
    }

    /**
     * Determines if has any blogs
     *
     * @return bool
     * @access public
     */
    public function hasBlogCategoriess(): bool
    {
        return ($this->blogCategories()->count() !== 0)? true : false;
    }

    /**
     * Determines if has a given blog
     *
     * @param App\Models\System\Blog|int $blogCategory
     *
     * @return bool
     * @access public
     */
    public function hasBlogCategory($blogCategory): bool
    {
        if (filled($blogCategory)) {
            if (is_numeric($blogCategory) && is_finite(intval($blogCategory))) {
                return $this->blogCategories()->where('id', intval($blogCategory))->exists();
            }

            if ($blogCategory instanceof BlogCategory) {
                return $this->blogCategories()->where('id', $blogCategory->id)->exists();
            }
        }

        return false;
    }

    /**
     * Assign a given resource
     *
     * @param App\Models\System\BlogCategory|int $blogCategory
     *
     * @return bool
     * @access public
     */
    public function assignBlogCategory($blogCategory): bool
    {
        if (!$this->hasBlog($blogCategory)) {
            if (is_numeric($blogCategory) && is_finite(intval($blogCategory))) {
                $blogCategory = BlogCategory::where('id', intval($blogCategory))
                            ->select(['id'])
                            ->firstOrFail();
            }

            if ($blogCategory instanceof BlogCategory) {
                return ($this->blogCategories()->save($blogCategory))? true : false;
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * Unassign a given resource
     *
     * @param App\Models\System\BlogCategory|int $blogCategory
     *
     * @return bool
     * @access public
     */
    public function unassignBlogCategory($blogCategory): bool
    {
        if ($this->hasBlog($blogCategory)) {
            if (is_numeric($blogCategory) && is_finite(intval($blogCategory))) {
                return ($this->blogCategories()->detach(intval($blogCategory)))? true : false;
            }

            if ($blogCategory instanceof BlogCategory) {
                return ($this->blogCategories()->detach($blogCategory->id))? true : false;
            }
        }

        return true;
    }
}
