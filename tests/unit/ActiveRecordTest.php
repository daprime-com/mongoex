<?php
namespace tests\unit;

use tests\unit\mocks\ActiveRecordMock;

class ActiveRecordTests extends TestCase
{    
    public function fixtures()
    {
        return [
            'records' => [
                'class' => ActiveRecordMock::className(),
                'data' => [
                    ['str' => 'string', 'integer' => 100]
                ]
            ]
        ];
    }
    
    public function testActiveMongoRecordAttributes()
    {
        $model = new ActiveRecordMock();
        $this->assertCount($model->getAttributesCount(), $model->attributes());
    }
    
    public function testReturnsIdFieldAsString()
    {
        $model = ActiveRecordMock::find()->one();
        $this->assertInstanceOf(ActiveRecordMock::className(), $model);
        $fields = $model->toArray();
        $this->assertTrue(is_string($fields['_id'])); 
        $this->assertTrue(is_string($model->id));
    }
}