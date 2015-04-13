<?php
namespace tests\unit;

use tests\unit\mocks\ActiveRecordMock;
use tests\unit\mocks\PartialRecordMock;

class PartialRecordTests extends TestCase
{        
    public function testFindQueryClass()
    {
        $query = PartialRecordMock::find();
        $this->assertInstanceOf(\mongoex\ActiveQuery::className(), $query);
        $this->assertTrue($query->hasParent());
    }
    
    public function testFindOneMethod()
    {
        $model = PartialRecordMock::findOne('oid1');
        $this->assertInstanceOf(PartialRecordMock::className(), $model);
        $this->assertEquals('partialstring1', $model->str);
        $this->assertEquals(2, $model->integer);
    }
    
    public function testFindMethod()
    {
        $models = PartialRecordMock::find()->where(['str' => 'partialstring1'])->all();
        $this->assertCount(1, $models);
        $this->assertInstanceOf(PartialRecordMock::className(), $models[0]);
        $this->assertEquals('partialstring1', $models[0]->str);
        $this->assertEquals(2, $models[0]->integer);
    }
    
    public function testNewModelCreation()
    {
        $model = new PartialRecordMock([
            'parentId' => 'parent1'
        ]);
        $model->str = 'partialstring6';
        $model->integer = 100;
        
        $this->assertModelSaved($model);
    }
    
    public function testExistingModelUpdate()
    {
        $model = PartialRecordMock::findOne('oid1');
        $this->assertEquals('partialstring1', $model->str);
        $this->assertEquals(2, $model->integer);
        
        $model->str = 'updatedstring';
        $this->assertModelSaved($model);
        $this->assertEquals('updatedstring', $model->str);
        $this->assertEquals(2, $model->integer);
        $model->refresh();
        $this->assertEquals('updatedstring', $model->str);
        $this->assertEquals(2, $model->integer);
    }
    
    public function testExistingModelDelete()
    {
        $model = PartialRecordMock::findOne('oid1');
        $this->assertEquals(1, $model->delete());
        $this->assertNull(PartialRecordMock::findOne('oid1'));
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
                    ]],
                    ['str' => 'string', 'integer' => 1, 'partial' => [
                        ['oid' => 'oid4', 'str' => 'partialstring4', 'integer' => 5],
                        ['oid' => 'oid5', 'str' => 'partialstring5', 'integer' => 6]
                    ]]
                ]
            ]
        ];
    }
}