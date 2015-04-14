<?php
namespace mongoex;

use yii\db\StaleObjectException;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class PartialRecord extends ActiveRecord
{
    public $parentId;

    protected static $parentModel;

    public static function primaryKey()
    {
        return ['oid'];
    }

    public static function find()
    {
        return parent::find()->whereParent(static::$parentModel);
    }

    public static function getCollection()
    {
		$parentClass = static::$parentModel[0];
		return $parentClass::getCollection();
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields[] = 'parentId';
        return $fields;
    }

    public function setAttributes($values, $safeOnly = true)
    {
    	parent::setAttributes($values, $safeOnly);
        if (isset($values['parentId'])) {
			$this->parentId = $values['parentId'];
        }
    }

    /**
     * @see ActiveRecord::delete()
     * @throws StaleObjectException
     */
    protected function deleteInternal()
    {
        // we do not check the return value of deleteAll() because it's possible
        // the record is already deleted in the database and thus the method will return 0
        $parentModel = static::$parentModel[0];
        $parentField = static::$parentModel[1];

        $condition = [
            '_id' => $this->parentId,
            $parentField.'.oid' => $this->getOldPrimaryKey()
        ];

        $lock = $this->optimisticLock();
        if ($lock !== null) {
            $condition[$parentField.'.'.$lock] = $this->$lock;
        }

        $result = $parentModel::getCollection()->update($condition, [
            '$pull' => [$parentField => ['oid' => $this->getOldPrimaryKey()]]
        ]);
        if ($lock !== null && !$result) {
            throw new StaleObjectException('The object being deleted is outdated.');
        }
        $this->setOldAttributes(null);
        return $result;
    }

    /**
     * @see ActiveRecord::insert()
     */
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

        $parentModel = static::$parentModel[0];
        $parentField = static::$parentModel[1];

        $newId = new \MongoId();
        $this->setAttribute('oid', $newId);
        $values['oid'] = $newId;

        $parentModel::getCollection()->update(['_id' => $this->parentId], [
            '$push' => [$parentField => $this->prepareData($values)]
        ]);

        $changedAttributes = array_fill_keys(array_keys($values), null);
        $this->setOldAttributes($values);
        $this->afterSave(true, $changedAttributes);
        return true;
    }

    /**
     * @see ActiveRecord::update()
     * @throws StaleObjectException
     */
    protected function updateInternal($attributes = null)
    {
        if (!$this->beforeSave(false)) {
            return false;
        }
        $values = $this->getDirtyAttributes($attributes);
        if (empty($values)) {
            $this->afterSave(false, $values);
            return 0;
        }
        $condition = $this->getOldPrimaryKey(true);
        $lock = $this->optimisticLock();
        if ($lock !== null) {
            if (!isset($values[$lock])) {
                $values[$lock] = $this->$lock + 1;
            }
            $condition[$lock] = $this->$lock;
        }

        $rows = $this->updateRecord($this->prepareData($values));

        if ($lock !== null && !$rows) {
            throw new StaleObjectException('The object being updated is outdated.');
        }
        $changedAttributes = [];
        foreach ($values as $name => $value) {
            $changedAttributes[$name] = $this->getOldAttribute($name);
            $this->setOldAttribute($name, $value);
        }
        $this->afterSave(false, $changedAttributes);
        return $rows;
    }

    protected function updateRecord(array $values)
    {
        $parentModel = static::$parentModel[0];
        $parentField = static::$parentModel[1];
        $data = [];

        foreach ($values as $field => $value) {
            $data[$parentField.'.$.'.$field] = $value;
        }

        return $parentModel::getCollection()->update(
            ['_id' => $this->parentId, $parentField.'.oid' => $this->getId(false)],
            $data
        );
    }

    protected function prepareData(array $values)
    {
        return $values;
    }
}
