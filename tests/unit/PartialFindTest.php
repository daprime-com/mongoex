<?php
namespace tests\unit;

use tests\unit\mocks\ActiveRecordMock;
use tests\unit\mocks\PartialRecordMock;

class PartialFindTests extends TestCase
{    
    public function testFindOneMethod()
    {
        $model = PartialRecordMock::findOne('oid1');
        $this->assertInstanceOf(PartialRecordMock::className(), $model);
        $this->assertEquals('partialstring1', $model->str);
        $this->assertEquals(2, $model->integer);
    }
    
    public function testFindAllMethod()
    {
        $models = PartialRecordMock::find()->all();
        $this->assertCount(5, $models);
    }
    
    public function testFindWhereAllMethod()
    {
        $models = PartialRecordMock::find()->where(['str' => 'partialstring3'])->all();
        $this->assertCount(1, $models);
        $this->assertInstanceOf(PartialRecordMock::className(), $models[0]);
        $this->assertEquals('partialstring3', $models[0]->str);
    }
    
    public function testWhereWithBetweenOperatorCondition()
    {
        $models = PartialRecordMock::find()->where(['between', 'integer', 2, 4])->all();
        $this->assertCount(3, $models);
    }
    
    public function testWhereWithAndOperatorCondition()
    {
        $model = PartialRecordMock::find()
            ->where(['and', ['str' => 'partialstring4'], ['integer' => 5]])
            ->one();
        
        $this->assertInstanceOf(PartialRecordMock::className(), $model);
        $this->assertEquals('partialstring4', $model->str);
        $this->assertEquals(5, $model->integer);
    }
    
    public function testWhereWithOrOperatorCondition()
    {
        $models = PartialRecordMock::find()
            ->where(['or', ['str' => 'partialstring4'], ['integer' => 4]])
            ->all();
        
        $this->assertCount(2, $models);
    }
    
    public function testWhereWithInOperatorCondition()
    {
        $models = PartialRecordMock::find()
            ->where(['in', 'integer', [2, 4, 6, 8]])
            ->all();
        
        $this->assertCount(3, $models);
        
        $models = PartialRecordMock::find()
            ->where(['not in', 'integer', [2, 4, 6, 8]])
            ->all();
        
        $this->assertCount(2, $models);
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
                    ['_id' => 'parent2', 'str' => 'string', 'integer' => 1, 'partial' => [
                        ['oid' => 'oid4', 'str' => 'partialstring4', 'integer' => 5],
                        ['oid' => 'oid5', 'str' => 'partialstring5', 'integer' => 6]
                    ]]
                ]
            ]
        ];
    }
}