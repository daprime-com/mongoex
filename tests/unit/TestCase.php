<?php
namespace tests\unit;

use Yii;

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
    
    public function setUp()
    {
        parent::setUp();
        $this->mockApp();
    }
    
    public function tearDown()
    {
        parent::tearDown();
        Yii::$app = null;
    }
}