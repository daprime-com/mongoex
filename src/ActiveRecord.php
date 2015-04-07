<?php
namespace mongoex;

use Yii;
use yii\mongodb\ActiveRecord as BaseActiveRecord;
use yii\base\InvalidConfigException;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class ActiveRecord extends BaseActiveRecord implements DataTypeInterface
{
    use DataTypeTrait;
    
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
    
    public function hasGg($class, $link)
    {
        /*$query = $class::find();
        $query->primaryModel = $this;
        $query->link = $link;
        $query->multiple = false;
        return $query;*/
    }
    
    /*public function fields()
    {
        $fields = parent::fields();
        if (isset($fields['_id'])) {
            $fields['_id'] = function ($model) {
                return (string)$model->_id;
            };
        }
        return $fields;
    }*/
    
    public function columns()
    {
        throw new InvalidConfigException('Method columns() must be implemented by child classes');
    }
}