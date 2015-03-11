<?php
namespace tests\unit;

use mongoex\ActiveRecord;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class ActiveRecordMock extends ActiveRecord
{            
    public static function collectionName()
    {
        return 'test_collection';    
    }
    
    public function rules()
    {
        return [
            [['str', 'integer'], 'required']
        ];
    }
    
    public function columns()
    {
        return [
            'str' =>     static::DATA_TYPE_STRING,
            'integer' => static::DATA_TYPE_INTEGER,
            'obj' =>     static::DATA_TYPE_OBJECT
        ];
    }
    
    //for testing only
    public function getAttributesCount()
    {
        return 4; //+_id
    }
}