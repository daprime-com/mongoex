<?php
namespace tests\unit;

use mongoex\ActiveEmbeddedRecord;

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
}