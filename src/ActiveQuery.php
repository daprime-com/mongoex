<?php
namespace mongoex;

use yii\mongodb\ActiveQuery as BaseActiveQuery;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class ActiveQuery extends BaseActiveQuery
{
    public function getCollection($db = null)
    {
        $collection = parent::getCollection($db);
        $modelClass = $this->modelClass;
        $prefix = $modelClass::prefix();
        if ($prefix) {
            $collection->prefix = $prefix;
        }
        return $collection;
    }
}
