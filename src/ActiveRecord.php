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
        $attributes = array_merge(static::primaryKey(), array_keys($this->columns()));
        $prefix = static::prefix();
        if ($prefix) {
            $attributes[] = 'parentId';
        }
        return $attributes;
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
    
    protected function insertInternal($attributes = null)
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
        $primaryKey = static::primaryKey()[0];
        $this->setAttribute($primaryKey, $newId);
        $values[$primaryKey] = $newId;
        $changedAttributes = array_fill_keys(array_keys($values), null);
        $this->setOldAttributes($values);
        $this->afterSave(true, $changedAttributes);
        return true;
    }
}
