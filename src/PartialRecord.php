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
    
    /**
     * @see ActiveRecord::insert()
     */
    /*protected function insertInternal($attributes = null)
    {
        if (!$this->beforeSave(true)) {
            return false;
        }
        $values = $this->getDirtyAttributes($attributes);
        if (empty($values)) {
            $currentAttributes = $this->getAttributes();
            foreach ($this->primaryKey() as $key) {
                if (isset($currentAttributes[$key])) {
                    $values[$key] = $currentAttributes[$key];
                }
            }
        }
        $newId = static::getCollection()->insert($values);
        $this->setAttribute('_id', $newId);
        $values['_id'] = $newId;
        $changedAttributes = array_fill_keys(array_keys($values), null);
        $this->setOldAttributes($values);
        $this->afterSave(true, $changedAttributes);
        return true;
    }*/
}