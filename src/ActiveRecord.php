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
    public static function find()
    {
        return Yii::createObject(\mongoex\ActiveQuery::className(), [get_called_class()]);
    }
    
    public static function primaryKey()
    {
        $prefix = static::prefix();
        if ($prefix === null) {
            return parent::primaryKey();
        }
        return ['oid'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return array_merge(static::primaryKey(), array_keys($this->columns()));
    }

    public function getId($stringify = true)
    {
    	if ($stringify) {
			return (string)$this->primaryKey;
    	}
        return $this->primaryKey;
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
    
    public static function prefix()
    {
        return null;
    }
    
    public static function getCollection()
    {
        $collection = parent::getCollection();
        $prefix = static::prefix();
        if ($prefix) {
            $collection->prefix = $prefix;
        }
        return $collection;
    }
}
