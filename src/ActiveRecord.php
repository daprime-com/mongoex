<?php
namespace mongoex;

use Yii;
use yii\mongodb\ActiveRecord as BaseActiveRecord;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class ActiveRecord extends BaseActiveRecord implements DataTypeInterface
{
    use DataTypeTrait;
    
    public function __set($name, $value) {
        var_dump($name);
        parent::__set($name, $value);
    }
    
    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return array_merge(static::primaryKey(), array_keys($this->columns()));
    }
    
    /**
     * @inheritdoc
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        return Yii::createObject(ActiveQuery::className(), [get_called_class()]);
    }
    
    public function fields()
    {
        $fields = parent::fields();
        if (isset($fields['_id'])) {
            $fields['_id'] = function ($model) {
                return (string)$model->_id;
            };
        }
        return $fields;
    }
    
    public function columns()
    {
        throw new InvalidConfigException('Method columns() must be implemented by child classes');
    }
    
    public function getFieldType($name)
    {
        $types = $this->columns();
        if (!array_key_exists($name, $types)) {
            return null;
        }
        return $types[$name];
    }
}