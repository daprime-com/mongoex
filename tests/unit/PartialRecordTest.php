<?php
namespace tests\unit;

use tests\unit\mocks\ActiveRecordMock;
use tests\unit\mocks\PartialRecordMock;

class PartialRecordTests extends TestCase
{    
    public function testPartialRecordParentDefinition()
    {
        $this->assertEquals(ActiveRecordMock::className(), PartialRecordMock::getParentClass());
        $this->assertEquals('partial', PartialRecordMock::getParentField());
    }
    
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
        
        $models = PartialRecordMock::find()
                ->where(['or', ['str' => 'partialstring1'], ['str' => 'partialstring4']])
                ->all();
        
        $this->assertCount(2, $models);
    }
    
    public function fixtures()
    {
        return [
            'records' => [
                'class' => ActiveRecordMock::className(),
                'data' => [
                    ['str' => 'string', 'integer' => 1, 'partial' => [
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