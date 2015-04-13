<?php
namespace mongoex;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
trait AggregatedQueryTrait
{
    public $parent;
    
    public function hasParent()
    {
        return $this->parent !== null;
    }
    
    public function whereParent(array $parentDefinition)
    {
        $this->parent = $parentDefinition;
        return $this;
    }
}