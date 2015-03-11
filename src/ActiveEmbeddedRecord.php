<?php
namespace mongoex;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 * @since 2.0
 */

class ActiveEmbeddedRecord extends ActiveRecord
{
    private $_parentModel;
    
    private $_fieldName; 
    
    public function __construct($parentModel, $fieldName, $config = [])
    {
        $this->_parentModel = $parentModel;
        $this->_fieldName = $fieldName;
        parent::__construct($config);
    }
    
    public function setParentModel(\yii\db\BaseActiveRecord $model)
    {
        $this->_parentModel = $model;
    }
    
    public function setFieldName($name)
    {
        $this->_fieldName = $name;
    }
    
    public function getFieldName()
    {
        return $this->_fieldName;
    }
    
    public function columns()
    {
        return [];
    }
    
    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['oid'];
    }
}
