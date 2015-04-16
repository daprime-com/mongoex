<?php
namespace mongoex;

use yii\mongodb\Database as BaseDatabase;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class Database extends BaseDatabase
{
    protected function selectCollection($name)
    {
        return \Yii::createObject([
            'class' => 'mongoex\Collection',
            'mongoCollection' => $this->mongoDb->selectCollection($name)
        ]);
    }
}