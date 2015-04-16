<?php
namespace tests\unit\mocks;

use mongoex\ActiveRecord;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class PartialRecordMock extends ActiveRecord
{            
    public static function collectionName()
    {
        return 'test_collection';    
    }
    
    public static function prefix()
    {
        return 'partial';
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
            'integer' => static::DATA_TYPE_INTEGER
        ];
    }
    
    //for testing only
    public function getAttributesCount()
    {
        return 3; //+_id
    }
}