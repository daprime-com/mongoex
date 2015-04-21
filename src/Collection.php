<?php
namespace mongoex;

use Yii;
use yii\mongodb\Collection as BaseCollection;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class Collection extends BaseCollection
{    
    public $prefix;
    
    public $multiple = true;
    
    public function hasPrefix()
    {
        return $this->prefix !== null;
    }
    
    public function insert($data, $options = [])
    {
        if (!$this->hasPrefix()) {
            return parent::insert($data, $options);
        }
        
        return $this->insertPartial($data, $options);
    }
    
    public function update($condition, $newData, $options = [])
    {
        if (!$this->hasPrefix()) {
            return parent::update($condition, $newData, $options);
        }
        
        return $this->updatePartial($condition, $newData);
    }
    
    public function remove($condition = [], $options = [])
    {
        if (!$this->hasPrefix()) {
            return parent::remove($condition, $options);
        }
        
        return $this->removePartial($condition, $options);
    }
    
    public function removePartial($condition = [], $options = [])
    {
        return parent::update([], [
            '$pull' => [$this->prefix => $condition]
        ], $options);
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
    
    public function find($condition = [], $fields = [])
    {
        if (!$this->hasPrefix()) {
            return parent::find($condition, $fields);
        }
        
        $cursor = new AggregationCursor($this);
        $cursor->pipeline('$match', $this->buildCondition($condition));
        return $cursor;
    }
    
    public function buildHashCondition($condition)
    {
        $result = parent::buildHashCondition($condition);
        if (!$this->hasPrefix()) {
			return $result;
        }
		return $this->applyPrefixes($result);
    }

    public function buildLikeCondition($operator, $operands)
    {
        $condition = parent::buildLikeCondition($operator, $operands);
        if (!$this->hasPrefix()) {
			return  $condition;
        }
        return $this->applyPrefixes($condition);
    }
    
    public function buildBetweenCondition($operator, $operands)
    {
        $condition = parent::buildBetweenCondition($operator, $operands);
        return $this->applyPrefixes($condition);
    }

    protected function applyPrefixes(array $data, $operator = null)
    {
        $result = [];
        foreach ($data as $key => $value) {
            if ($key === '_id') {
                $value = $this->ensureMongoId($value);
            }
            $result[$this->appendKeyPrefix($key, $operator)] = $value;
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