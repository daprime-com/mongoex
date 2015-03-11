<?php
namespace tests\unit;

class ActiveQueryTests extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        ActiveRecordMock::getCollection()->remove();
    }
    
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