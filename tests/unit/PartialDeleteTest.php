<?php
namespace tests\unit;

use tests\unit\mocks\ActiveRecordMock;
use tests\unit\mocks\PartialRecordMock;

class PartialDeleteTests extends TestCase
{    
    public function testExistingModelDelete()
    {
        $model = PartialRecordMock::findOne('oid1');
        $this->assertEquals('partialstring1', $model->str);
        
        $this->assertEquals(1, $model->delete());
        $this->assertInstanceOf(ActiveRecordMock::className(), ActiveRecordMock::find()->one());
        $this->assertNull(PartialRecordMock::findOne('oid1'));
    } 
    
    public function testExistingCollectionPartialDelete()
    {
        $model = PartialRecordMock::findOne('oid2');
        $this->assertEquals('partialstring2', $model->str);
        
        PartialRecordMock::getCollection()->remove(['oid' => 'oid2']);
        $this->assertInstanceOf(ActiveRecordMock::className(), ActiveRecordMock::find()->one());
        $this->assertNull(PartialRecordMock::findOne('oid2'));
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