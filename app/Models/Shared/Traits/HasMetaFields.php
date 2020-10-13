<?php

namespace App\Models\Shared\Traits;

/**
 * Has Meta Fields Trait
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Models\Shared\Traits
 */
trait HasMetaFields
{
    /**
     * Returns a meta field
     *
     * @param   string $name
     * @return  mixed
     */
    public function getMetaField($name)
    {
        if (filled($name)) {
            if (filled($this->meta_fields)) {
                if (strpos($name, '->') !== false) {
                    $list = explode('->', $name);

                    $meta_field = $this->meta_fields;

                    foreach ($list as $key) {
                        if (isset($meta_field->{$key})) {
                             $meta_field = $meta_field->{$key};
                        }
                    }

                    return $meta_field;
                } else {
                    if (isset($this->meta_fields->{$name})) {
                        return $this->meta_fields->{$name};
                    }
                }
            }
        }

        return null;
    }

    /**
     * Set meta field value
     *
     * @param  string $name
     * @param  mixed $value
     * @return bool
     */
    public function setMetaField($name, $value = null, $autosave = true): bool
    {
        if (filled($name)) {
            if (filled($this->meta_fields)) {
                $metaFields = (array) $this->meta_fields;
                $metaFields[$name] = $value;
                $this->meta_fields = json_decode(json_encode($metaFields));
            } else {
                $this->meta_fields = json_decode(json_encode([$name=>$value]));
            }

            if ($autosave) {
                return $this->save();
            } else {
                return true;
            }
        }

        return false;
    }
}
