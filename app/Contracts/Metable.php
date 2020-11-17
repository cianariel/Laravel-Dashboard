<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;

/**
 * Interface Repository
 *
 * @author      Antonio Vargas <localhost.80@gmail.com>
 * @copyright   2020 MDRepTime, LLC
 * @package     App\Contracts
 */
interface Metable
{
    /**
     * @return \Spatie\SchemalessAttributes\SchemalessAttributes
     */
    public function getMetaFieldsAttribute() : SchemalessAttributes;

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithMetaFields(): Builder;
}