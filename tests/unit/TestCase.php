<?php
namespace tests\unit;

use Yii;
use tests\unit\mocks\ActiveRecordMock;

/**
 * @author Igor Murujev <imurujev@gmail.com>
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function mockApp()
    {
        $config = require(__DIR__ . '/_config.php');
        new \yii\console\Application($config);
    }
    
    protected function fixtures()
    {
        return null;
    }
    
    public function setUp()
    {
        parent::setUp();
        $this->mockApp();
        $this->loadFixtures();
    }
    
    public function loadFixtures()
    {
        $fixtures = $this->fixtures();
        if ($fixtures === null) {
            return true;
        }
        
        foreach ($fixtures as $definition) {
            if (!isset($definition['class'])) {
                throw new \Exception('class property must be defined in fixture definition');
            }
            $class = $definition['class'];
            $class::getCollection()->remove();
            $data = isset($definition['data']) ? $definition['data'] : null;
            if (is_array($data)) {
                $class::getCollection()->batchInsert($data);
            }
        }
    }
    
    public function tearDown()
    {
        parent::tearDown();
        Yii::$app = null;
    }
    
    protected function assertModelSaved($model)
    {
        $saved = $model->save();
        $this->assertCount(0, $model->getErrors(), 'Errors: ' . json_encode($model->getErrors()));
        $this->assertTrue($saved);
    }
}