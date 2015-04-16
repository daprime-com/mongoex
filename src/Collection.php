<?php
namespace mongoex;

use yii\mongodb\Collection as BaseCollection;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class Collection extends BaseCollection
{
    public $prefix;
    
    public function hasPrefix()
    {
        return $this->prefix !== null;
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