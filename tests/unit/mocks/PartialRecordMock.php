<?php
namespace tests\unit\mocks;

use mongoex\PartialRecord;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class PartialRecordMock extends PartialRecord
{            
    protected static $parentModel = ['tests\unit\mocks\ActiveRecordMock', 'partial'];
    
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