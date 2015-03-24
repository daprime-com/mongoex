<?php
namespace tests\unit;

use mongoex\ActiveRecord;
use mongoex\validators\MongoObjectValidator;
use mongoex\validators\MongoArrayValidator;

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
            [['str', 'integer'], 'required'],
            ['obj', MongoObjectValidator::className(), 'rules' => [
                ['str', 'string'],
                ['int', 'required']
            ]],
            ['arr', MongoArrayValidator::className(), 'rules' => [
                ['str', 'string'],
                ['int', 'required']
            ]]
        ];
    }
    
    public function columns()
    {
        return [
            'str' =>     static::DATA_TYPE_STRING,
            'integer' => static::DATA_TYPE_INTEGER,
            'obj' => static::DATA_TYPE_OBJECT,
            'arr' => static::DATA_TYPE_ARRAY
        ];
    }
    
    //for testing only
    public function getAttributesCount()
    {
        return 5; //+_id
    }
}