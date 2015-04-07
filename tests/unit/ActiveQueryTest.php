<?php
namespace tests\unit;

use tests\unit\mocks\ActiveRecordMock;

class ActiveQueryTests extends TestCase
{    
    public function testFindReturnsMongoexQuery()
    {
        $this->assertInstanceOf(\mongoex\ActiveQuery::className(), ActiveRecordMock::find());
    }
}