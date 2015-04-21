<?php
namespace tests\unit;

use tests\unit\mocks\ActiveRecordMock;
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
        $this->assertInstanceOf(PartialRecordMock::className(), PartialRecordMock::findOne(['str' => 'newstring']));
    } 
    
    public function testCollectionInsert()
    {
        $result = PartialRecordMock::getCollection()->insert(['str' => 'insertstr', 'parentId' => 'parent1']);
        $this->assertInstanceOf('MongoId', $result);
    }
    
    public function fixtures()
    {
        return [
            'records' => [
                'class' => ActiveRecordMock::className(),
                'data' => [
                    ['_id' => 'parent1', 'str' => 'string', 'integer' => 1]
                ]
            ]
        ];
    }
}