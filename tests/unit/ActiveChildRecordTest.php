<?php
namespace tests\unit;

use tests\unit\mocks\ActiveRecordMock;
use tests\unit\mocks\ActiveRelatedRecordMock;

class ActiveChildRecordTests extends TestCase
{    
    public function setUp()
    {
        parent::setUp();
        ActiveRecordMock::getCollection()->remove();
    }
    
    public function testChildRecordFindOneByPrimary()
    {
        $mongoChildId = new \MongoId();
        ActiveRecordMock::getCollection()->insert([
            'str' => 'string',
            'integer' => 10,
            'object' => [
                '_id' => $mongoChildId,
                'str' => 'childstring',
                'integer' => 10
            ]
        ]);
        
        $childModel = ActiveRelatedRecordMock::findOne($mongoChildId);
        $this->assertInstanceOf(ActiveRelatedRecordMock::className(), $childModel);
        $this->assertEquals($childModel->_id, $mongoChildId);
        $this->assertEquals($childModel->str, 'childstring');
    }
    
    public function testChildRecordFindOneByCondition()
    {
        $mongoChildId = new \MongoId();
        ActiveRecordMock::getCollection()->insert([
            'str' => 'string',
            'integer' => 10,
            'object' => [
                '_id' => $mongoChildId,
                'str' => 'childstring',
                'integer' => 10
            ]
        ]);
        
        $childModel = ActiveRelatedRecordMock::find()->where(['str' => 'childstring'])->one();
        $this->assertInstanceOf(ActiveRelatedRecordMock::className(), $childModel);
        $this->assertEquals($childModel->_id, $mongoChildId);
        $this->assertEquals($childModel->str, 'childstring');
    }

    public function testChildRecordFindManyByCondition()
    {
        ActiveRecordMock::getCollection()->batchInsert([
            [
                'object' => ['_id' => new \MongoId(), 'str' => 'childstring']
            ],
            [
                'object' => ['_id' => new \MongoId(), 'str' => 'childstring']
            ],
            [
                'object' => ['_id' => new \MongoId(), 'str' => 'anothervalue']
            ]
        ]);
        
        $childModels = ActiveRelatedRecordMock::find()->where(['str' => 'childstring'])->all();
        $this->assertCount(2, $childModels);
        foreach ($childModels as $childModel) {
            $this->assertInstanceOf(ActiveRelatedRecordMock::className(), $childModel);
            $this->assertEquals($childModel->str, 'childstring');
        }
    }
    
    public function testChildRecordFindOrderBy()
    {
        ActiveRecordMock::getCollection()->batchInsert([
            [
                'object' => ['_id' => new \MongoId(), 'str' => 'childstring', 'integer' => 15]
            ],
            [
                'object' => ['_id' => new \MongoId(), 'str' => 'childstring', 'integer' => 10]
            ],
            [
                'object' => ['_id' => new \MongoId(), 'str' => 'childstring', 'integer' => 5]
            ],
            [
                'object' => ['_id' => new \MongoId(), 'str' => 'anothervalue', 'integer' => 0]
            ]
        ]);
        
        $childModels = ActiveRelatedRecordMock::find()
                ->where(['str' => 'childstring'])
                ->orderBy('integer ASC')->all();
        
        $this->assertCount(3, $childModels);
        $this->assertEquals($childModels[0]->integer, 5);
        $this->assertEquals($childModels[1]->integer, 10);
        $this->assertEquals($childModels[2]->integer, 15);
        
        $childModels = ActiveRelatedRecordMock::find()
                ->where(['str' => 'childstring'])
                ->orderBy('integer DESC')->all();
        
        $this->assertCount(3, $childModels);
        $this->assertEquals($childModels[0]->integer, 15);
        $this->assertEquals($childModels[1]->integer, 10);
        $this->assertEquals($childModels[2]->integer, 5);
    }
}