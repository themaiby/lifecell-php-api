<?php
/**
 * Created by PhpStorm.
 * User: Hope
 * Date: 06.08.2018
 * Time: 21:59
 */

namespace LifecellApi\Models;

class Model
{
    protected $data;

    protected $mappingClasses = [];
    protected $propNameMap = [];

    /**
     * Model constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->fromArray($data);
    }

    /**
     * @param array $data
     * @return $this
     */
    public function fromArray(array $data)
    {
        foreach ($data as $key => $val) {
            if (is_int($key)) {
                if (method_exists($this, "add")) {
                    $this->add($val);
                }
            }
            $propertyName = $key;
            $ourPropertyName = array_search($propertyName, $this->propNameMap);
            if ($ourPropertyName && isset($data[$ourPropertyName])) {
                $propertyName = $ourPropertyName;
            }
            if (!empty($this->propNameMap)) {
                if (array_key_exists($key, $this->propNameMap)) {
                    $propertyName = $this->propNameMap[$key];
                }
            }
            if (property_exists($this, $propertyName)) {
                if (isset($this->mappingClasses[$propertyName])) {
                    $this->{$propertyName} = new $this->mappingClasses[$propertyName]($val);
                } else {
                    $this->{$propertyName} = $val;
                }
            }
        }
        return $this;
    }
}