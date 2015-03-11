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
    
    public function __get($name)
    {
        $value = parent::__get($name);
        if ($name === '_id' && is_object($value)) {
            return (string)$value;
        }
        
        if (is_array($value)) {
            return $this->getEmbedded($name, $value);
            
        }
        return $value;
    }
    
    protected function getEmbedded($name, array $value)
    {
        //$isArray = array_keys($value) === range(0, count($value) - 1);
        $embedded = new ActiveEmbeddedRecord($this, $name);
        return $embedded;
    }
    
    public function columns()
    {
        throw new InvalidConfigException('Method columns() must be implemented by child classes');
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
}