<?php
namespace mongoex;

use Yii;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
trait CollectionPartialTrait
{
    public $prefix;
    
    public $multiple = true;
    
    public function hasPrefix()
    {
        return $this->prefix !== null;
    }
    
    public function insertPartial($data, $options = [])
    {
        if (!isset($data['parentId'])) {
            throw new \Exception('Parent ID attribute must be defined to insert new partial document');
        }
        
        $parentId = $data['parentId'];
        unset($data['parentId']);
        $newId = new \MongoId();
        $data['oid'] = (string)$newId;

        $result = parent::update(['parent._id' => $parentId], [
            '$push' => [$this->prefix => $data]
        ], $options);
        
        if ($result === 0) {
            return false;
        }
        return $newId;
    }
    
    public function updatePartial($condition, $newData)
    {
        $condition = $this->buildCondition($condition);
        $options = ['w' => 1, 'multiple' => true];
        $newData = $this->applyPrefixes($newData, '$');

        $token = $this->composeLogToken('update', [$condition, $newData, $options]);
        Yii::info($token, __METHOD__);
        try {
            Yii::beginProfile($token, __METHOD__);
            $result = $this->mongoCollection->update($condition, ['$set' => $newData], $options);
            $this->tryResultError($result);
            Yii::endProfile($token, __METHOD__);
            if (is_array($result) && array_key_exists('n', $result)) {
                return $result['n'];
            } else {
                return true;
            }
        } catch (\Exception $e) {
            Yii::endProfile($token, __METHOD__);
            throw new \Exception($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
    
    public function removePartial($condition = [], $options = [])
    {
        return parent::update([], [
            '$pull' => [$this->prefix => $condition]
        ], $options);
    }
    
    protected function applyPrefixes(array $data, $operator = null)
    {
        $result = [];
        foreach ($data as $key => $value) {
        	$key = $this->appendKeyPrefix($key, $operator);
            if ($key === '_id') {
                $value = $this->ensureMongoId($value);
            }
            $result[$key] = $value;
        }
		return $result;
    }

    protected function appendKeyPrefix($key, $operator = null)
    {
    	if (strpos($key, 'parent.') === 0) {
			return substr($key, 7);
    	}
    	elseif ($key === 'parentId') {
			return '_id';
    	}
    	else {
			return $this->prefix . '.' . ($operator === null ? null : $operator . '.') .  $key;
    	}
    }
}
