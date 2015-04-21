<?php
namespace tests\unit;

use tests\unit\mocks\ActiveRecordMock;
use tests\unit\mocks\PartialRecordMock;

class PartialUpdateTests extends TestCase
{    
    public function testExistingModelUpdate()
    {
        $model = PartialRecordMock::findOne('oid1');
        $this->assertInstanceOf(PartialRecordMock::className(), $model);
        
        $model->str = 'updatedstring';
        $this->assertModelSaved($model);
        
        $model = PartialRecordMock::findOne('oid1');
        $this->assertEquals('updatedstring', $model->str);
    } 
    
    public function testCollectionInsert()
    {
        $result = PartialRecordMock::getCollection()->update(['oid' => 'oid2'], [
            'str' => 'updatedstring'
        ]);
        
        $model = PartialRecordMock::findOne('oid2');
        $this->assertEquals('updatedstring', $model->str);
    }
    
    public function fixtures()
    {
        return [
            'records' => [
                'class' => ActiveRecordMock::className(),
                'data' => [
                    ['_id' => 'parent1', 'str' => 'string', 'integer' => 1, 'partial' => [
                        ['oid' => 'oid1', 'str' => 'partialstring1', 'integer' => 2],
                        ['oid' => 'oid2', 'str' => 'partialstring2', 'integer' => 3],
                        ['oid' => 'oid3', 'str' => 'partialstring3', 'integer' => 4]
                    ]]
                ]
            ]
        ];
    }
}