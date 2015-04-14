<?php
namespace mongoex;

use yii\mongodb\ActiveQuery as BaseActiveQuery;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class ActiveQuery extends BaseActiveQuery
{
    use AggregatedQueryTrait;

    protected function buildCursor($db = null)
    {
        if (!$this->hasParent()) {
            return parent::buildCursor($db);
        }
        return $this->buildPartialCursor($db);
    }

    protected function buildPartialCursor($db = null)
    {
        $parentClass = $this->parent[0];
        $parentField = $this->parent[1];

        return new PartialCursor([
            'data' => $parentClass::getCollection()->aggregate($this->buildPipeline($parentField))
        ]);
    }

    /**
     * @param string $parentField
     * @return array
     */
    protected function buildPipeline($parentField)
    {
        $matchCondition = null;
        if ($this->composePartialCondition($parentField)) {
            $matchCondition = ['$match' => $this->composePartialCondition($parentField)];
        }

        $pipelines = [];
        $pipelines[] = ['$unwind' => '$'.$parentField];
        if ($matchCondition) {
            $pipelines[] = $matchCondition;
        }
        $pipelines[] = ['$project' => ['result' => '$'.$parentField, 'parentId' => '$_id', '_id' => 0]];

        if (!empty($this->orderBy)) {
            $pipelines[] = ['$sort' => $this->composeSort()];
        }

        if ($this->limit > 0) {
            $pipelines[] = ['$limit' => $this->limit];
        }

        if ($this->offset > 0) {
            $pipelines[] = ['$skip' => $this->offset];
        }

        return $pipelines;
    }

    private function composeSort()
    {
        $sort = [];
        foreach ($this->orderBy as $fieldName => $sortOrder) {
            $sort[$fieldName] = $sortOrder === SORT_DESC ? \MongoCollection::DESCENDING : \MongoCollection::ASCENDING;
        }
        return $sort;
    }

    private function composePartialCondition($parentField)
    {
        if ($this->where === null) {
            return null;
        }

        $match = [];
        foreach ($this->where as $field => $value) {
            if ($field === 'parentId') {
                $match['_id'] = $value;
            }
            else {
                $match[$parentField . '.' . $field] = $value;
            }
        }
        return $match;
    }
}
