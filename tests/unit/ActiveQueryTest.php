<?php
namespace tests\unit;

use tests\unit\mocks\ActiveRecordMock;

class ActiveQueryTests extends TestCase
{    
    public function testFindReturnsMongoexQuery()
    {
        $this->assertInstanceOf(\mongoex\ActiveQuery::className(), ActiveRecordMock::find());
    }
    
    public function testActiveQueryIdStringifying()
    {
        $model = new ActiveRecordMock();
        $model->str = 'string';
        $model->integer = 5;

        $this->assertTrue($model->save());

        $found = ActiveRecordMock::findOne($model->_id);
        $this->assertTrue(is_string($found->_id));
    }
}