<?php

namespace App\Models\Shared\Traits;

use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\SchemalessAttributes;

/**
 * Has Meta Fields Trait
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package App\Models\Shared\Traits
 */
trait HasMetaFields
{

    /**
     * @return \Spatie\SchemalessAttributes\SchemalessAttributes
     */
    public function getMetaFieldsAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'meta_fields');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithMetaFields(): Builder
    {
        return SchemalessAttributes::scopeWithSchemalessAttributes('meta_fields');
    }

    /**
     * Returns a meta field
     *
     * @param   string $name
     * @return  mixed
     */
    public function getMetaField($name, $defaultValue = null)
    {
        if(strpos($name, '->') !== false) {
            $name = str_replace('->', '.', $name);
        }

        return (filled($defaultValue)) ? $this->meta_fields->get($name, $defaultValue) : $this->meta_fields->get($name);
    }

    /**
     * Set meta field value
     *
     * @param  string $name
     * @param  mixed $value
     * @return bool
     */
    public function setMetaField($name, $value = null, bool $autosave = false): bool
    {
        if(strpos($name, '->') !== false) {
            $name = str_replace('->', '.', $name);
        }

        $this->meta_fields->set($name, $value);

        if($autosave === true) {
            return $this->save();
        }

        return true;
    }
}
