<?php
namespace mongoex;

use yii\mongodb\Connection as BaseConnection;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class Connection extends BaseConnection
{
    protected function selectDatabase($name)
    {
        $this->open();
        return \Yii::createObject([
            'class' => 'mongoex\Database',
            'mongoDb' => $this->mongoClient->selectDB($name)
        ]);
    }

}