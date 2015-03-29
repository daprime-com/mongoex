<?php
namespace mongoex;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class ActiveEmbeddedQuery extends ActiveQuery
{    
    protected function fetchRowsInternal($cursor, $all, $indexBy)
    {
        
        $result = [];
        if ($all) {
            foreach ($cursor as $row) {
                $row = $row[$this->parentField];
                if ($indexBy !== null) {
                    if (is_string($indexBy)) {
                        $key = $row[$indexBy];
                    } else {
                        $key = call_user_func($indexBy, $row);
                    }
                    $result[$key] = $row;
                } else {
                    $result[] = $row;
                }
            }
        } else {
            if ($cursor->hasNext()) {
                $result = $cursor->getNext()[$this->parentField];
            } else {
                $result = false;
            }
        }
        return $result;
    }
    
    protected function getParentField()
    {
        $modelClass = $this->modelClass;
        $parentModel = $modelClass::parentModel();
        return isset($parentModel[1]) ? $parentModel[1] : null;
    }
    
    protected function getParentModel()
    {
        $modelClass = $this->modelClass;
        $parentModel = $modelClass::parentModel();
        return isset($parentModel[0]) ? $parentModel[0] : null;
    }
    
    protected function buildCursor($db = null)
    {
        $modelClass = $this->modelClass;
        $primaryModel = $this->parentModel;
        $primaryField = $this->parentField;
        
        $cursor = $primaryModel::getCollection()->find(
            $this->composeMatchCondition($primaryField),
            [$primaryField => 1, '_id' => 0]
        );

        if (!empty($this->orderBy)) {
            $cursor->sort($this->composeSort());
        }
        return $cursor;
    }
    
    private function composeSort()
    {
        $sort = [];
        foreach ($this->orderBy as $fieldName => $sortOrder) {
            $sort[$this->parentField . '.' . $fieldName] = $sortOrder === SORT_DESC ? 
                    \MongoCollection::DESCENDING : \MongoCollection::ASCENDING;
        }
        return $sort;
    }
    
    protected function composeMatchCondition($primaryField)
    {
        $match = [];
        foreach ($this->where as $field => $value) {
            $match[$primaryField.'.'.$field] = $value;
        }
        return $match;
    }
}