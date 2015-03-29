<?php
namespace mongoex;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
trait AggregatedQueryTrait
{
    private $_aggregation;
    
    public function findChild($condition)
    {
        $this->_aggregation = $condition;
    }
    
    public function one($db = null)
    {
        $modelClass = $this->modelClass;
        if ($modelClass::parentModel() === null) {
            return parent::one($db);
        }
        
        $row = $this->unwind($modelClass::parentModel());
        var_dump($row);exit;
        if ($row !== false) {
            $models = $this->populate([$row]);
            return reset($models) ?: null;
        } else {
            return null;
        }
    }
    
    protected function unwind(array $parentModel)
    {
        $primaryModel = $parentModel[0];
        $primaryField = $parentModel[1];
        
        $primaryCollection = $primaryModel::getCollection();
        $match = [];
        foreach ($this->_aggregation as $field => $value) {
            $match[$primaryField . '.' . $field] = $value;
        }
        $result = $primaryCollection->aggregate([
            ['$match' => $match],
            //['$unwind' => '$'.$primaryField]
        ]);
        
        return $result[0][$primaryField];
    }
}