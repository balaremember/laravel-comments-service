<?php

namespace Balaremember\LaravelCommentsService\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseEntity
 * @property int $id
 */
class BaseEntity extends Model
{
    public $fieldMap = [];
    public $relationMap = [];
    
    /**
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->relations)) {
            return parent::getAttribute($key);
        } elseif (method_exists($this, $key)) {
            return parent::getAttribute($key);
        } else {
            return parent::getAttribute(snake_case($key));
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        return parent::setAttribute(snake_case($key), $value);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
        $result = [];
        foreach ($data as $key => $item) {
            $result[camel_case($key)] = $item;
        }

        return $result;
    }
}
