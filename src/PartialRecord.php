<?php
namespace mongoex;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class PartialRecord extends ActiveRecord
{
    public $parentId;
    
    protected static $parentModel;
    
    public static function getParentClass()
    {
        return static::$parentModel[0];
    }
    
    public static function getParentField()
    {
        return static::$parentModel[1];
    }
    
    public static function primaryKey() 
    {
        return ['oid'];
    }
    
    public static function find()
    {
        return parent::find()->whereParent(static::$parentModel);
    }
}