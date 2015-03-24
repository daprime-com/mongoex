<?php
namespace mongoex;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class Embedded
{
    private $name ;
    function __construct($name, $value) {
        $this->{$name} = $value;
        $this->name = $value ;
    }

    public function __get($name) {
        if (! isset ( $this->{$name} )) {
            $this->{$name} = new Value ( $name, null );
        }

        if ($name == $this->name) {
            return $this->value;
        }

        return $this->{$name};
    }

    public function __set($name, $value) {
        if (is_array ( $value )) {
            array_walk ( $value, function (&$item, $key) {
                $item = new Value ( $key, $item );
            } );
        }
        $this->{$name} = $value;
    }

    public function __toString() {
        return (string) $this->name ;
    }   
}