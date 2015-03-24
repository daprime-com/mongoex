<?php
namespace tests\unit;

class ActiveRecordTests extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        ActiveRecordMock::getCollection()->remove();
    }
    
    public function testActiveMongoRecordAttributes()
    {
        $model = new ActiveRecordMock();
        $this->assertCount($model->getAttributesCount(), $model->attributes());
    }
    
    public function testToArrayMethodIdStringifying()
    {
        $model = new ActiveRecordMock();
        $model->str = 'test';
        $model->integer = '5';
        
        $this->assertTrue($model->save());
        $toArray = $model->toArray();
        $this->assertTrue(is_string($toArray['_id']));
    }
    
    public function testObjectFieldsValidation()
    {
        $model = new ActiveRecordMock();
        $model->str = 'test';
        $model->integer = '5';
        
        //obj.int is required
        $model->obj = [
            'str' => 'aaaaa'
        ];
        
        $this->assertFalse($model->save());
        $this->assertArrayHasKey('obj.int', $model->getErrors());
    }
    
    public function testArrayFieldsValidation()
    {
        $model = new ActiveRecordMock();
        $model->str = 'test';
        $model->integer = '5';
        
        //arr.$.int is required
        $model->arr = [
            ['str' => 'aaaaa', 'int' => 100],
            ['str' => 'bbbbb']
        ];
        
        $this->assertFalse($model->save());
        $this->assertArrayHasKey('arr.1.int', $model->getErrors());
    }
    
    public function testSettingValueOfObjectAttribute()
    {
        $model = new ActiveRecordMock();
        $model->arr[0] = ['str' => 'aaa', 'int' => 100];
    }
}