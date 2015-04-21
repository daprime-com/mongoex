<?php
namespace mongoex;

use yii\mongodb\Collection as BaseCollection;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class Collection extends BaseCollection
{    
    use CollectionPartialTrait;
    
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
}