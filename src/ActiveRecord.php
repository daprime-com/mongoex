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
    
    public function getId()
    {
        return (string)$this->primaryKey;
    }
    
    public function fields()
    {
    	$primaryKey = static::primaryKey()[0];
        $fields = parent::fields();
        $fields[$primaryKey] = function($model){
            return $model->id;
        };
        return $fields;
    }
    
    public function columns()
    {
        throw new InvalidConfigException('Method columns() must be implemented by child classes');
    }
}
