<?php
namespace mongoex;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
trait DataTypeTrait
{
    public function getFieldType($name)
    {
        $types = $this->columns();
        if (!array_key_exists($name, $type)) {
            return null;
        }
        return $types[$name];
    }
}