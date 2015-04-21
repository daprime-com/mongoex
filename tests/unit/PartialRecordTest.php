<?php
namespace tests\unit;

use tests\unit\mocks\PartialRecordMock;
use mongoex\Collection;

class PartialRecordTests extends TestCase
{    
    public function testGetCollectionMethod()
    {
        $collection = PartialRecordMock::getCollection();
        $this->assertInstanceOf(Collection::className(), $collection);
        $this->assertEquals('partial', $collection->prefix);
    }
}