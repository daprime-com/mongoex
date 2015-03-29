<?php
namespace mongoex;

use yii\base\InvalidConfigException;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class ActiveEmbeddedRecord extends ActiveRecord
{
    protected $isPrimaryModel = false;
    
    public static function parentModel()
    {
        throw new InvalidConfigException('Method parentModel() must be implemented by child classes');
    }
    
    /**
     * @inheritdoc
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        return \Yii::createObject(ActiveEmbeddedQuery::className(), [get_called_class()]);
    }
}