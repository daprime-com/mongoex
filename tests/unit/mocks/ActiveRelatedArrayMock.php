<?php
namespace tests\unit\mocks;

use mongoex\ActiveEmbeddedRecord;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class ActiveRelatedArrayMock extends ActiveEmbeddedRecord
{                
    public static function parentModel()
    {
        return [ActiveRecordMock::className(), 'array'];
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
}