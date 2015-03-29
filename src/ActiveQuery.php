<?php
namespace mongoex;

use yii\mongodb\ActiveQuery as BaseActiveQuery;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class ActiveQuery extends BaseActiveQuery
{    
    /**
     * 
     */
    public function populate($rows)
    {
        if (empty($rows)) {
            return [];
        }
        
        foreach ($rows as $key => $row) {
            if (isset($row['_id']) && is_object($row['_id'])) {
                $rows[$key]['_id'] = (string)$row['_id'];
            }
        }
        return parent::populate($rows);
    }
}