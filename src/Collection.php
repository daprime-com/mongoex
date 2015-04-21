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
        
        if (!isset($data['parentId'])) {
            throw new \Exception('Parent ID attribute must be defined to insert new partial document');
        }
        
        $parentId = $data['parentId'];
        unset($data['parentId']);
        $newId = new \MongoId();
        $data['oid'] = $newId;

        $this->update(['_id' => $parentId], [
            '$push' => [$this->prefix => $data]
        ]);
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

    protected function applyPrefixes(array $data)
    {
		$keys = array_map([$this, 'appendKeyPrefix'], array_keys($data));
		$result = array_combine($keys, $data);
		if (isset($result['_id'])) {
			$result['_id'] = $this->ensureMongoId($result['_id']);
		}
		return $result;
    }

    protected function appendKeyPrefix($key)
    {
    	if (strpos($key, 'parent.') === 0) {
			return substr($key, 7);
    	}
    	elseif ($key === 'parentId') {
			return '_id';
    	}
    	else {
			return $this->prefix . '.' . $key;
    	}
    }
}