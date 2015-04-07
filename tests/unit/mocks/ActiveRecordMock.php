<?php
namespace tests\unit\mocks;

use mongoex\ActiveRecord;
use mongoex\validators\MongoObjectValidator;

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
            'integer' => static::DATA_TYPE_INTEGER
        ];
    }
    
    //for testing only
    public function getAttributesCount()
    {
        return 3; //+_id
    }
}