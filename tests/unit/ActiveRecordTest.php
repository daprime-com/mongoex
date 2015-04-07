<?php
namespace tests\unit;

use tests\unit\mocks\ActiveRecordMock;

class ActiveRecordTests extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    
    public function testActiveMongoRecordAttributes()
    {
        $model = new ActiveRecordMock();
        $this->assertCount($model->getAttributesCount(), $model->attributes());
    }
}