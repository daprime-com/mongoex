<?php
namespace tests\unit;

use tests\unit\mocks\PartialRecordMock;

class PartialCreateTests extends TestCase
{    
    /**
     * @expectedException \Exception
     * @expectedExceptionMessageRegExp /^Parent ID/
     */
    public function testNoParentIdException()
    {
        $model = new PartialRecordMock();
        $model->str = 'newstring';
        $model->integer = 100;
        
        $model->save();
    }
    
    public function testNewModelCreation()
    {
        $model = new PartialRecordMock();
        $model->str = 'newstring';
        $model->integer = 100;
        $model->parentId = 'parent1';
        
        $this->assertModelSaved($model);
    }
}