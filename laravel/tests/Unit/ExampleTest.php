<?php

namespace Tests\Unit;

use App\Services\TestService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * @var TestService
     */
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TestService();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    public function testVariables()
    {
        $bool = false;
        $number = 100;
        $arr = ['Laravel', 'PHP', 'test'];
        $obj = null;

        // 断言变量值是否为假，与 assertTrue 相对
        $this->assertFalse($bool);
        // 断言给定变量值是否与期望值相等，与 assertNotEquals 相对
        $this->assertEquals(100, $number);
        // 断言数组中是否包含给定值，与 assertNotContains 相对
        $this->assertContains('test', $arr);
        // 断言数组长度是否与期望值相等，与 assertNotCount 相对
        $this->assertCount(3, $arr);
        // 断言数组是否不会空，与 assertEmpty 相对
        $this->assertNotEmpty($arr);
        // 断言变量是否为 null，与 assertNotNull 相对
        $this->assertNull($obj);
    }

    public function testOutput()
    {
        $this->expectOutputString('学院君');
        echo '学院君';
    }

    public function testOutputRegex()
    {
        $this->expectOutputRegex('/Laravel/i');
        echo 'Laravel学院';
    }

    public function testException()
    {
        $this->expectException(\Exception::class);
        $service = new TestService();
        $service->invalidArgument();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionAnnotation()
    {
        $this->service->invalidArgument();
    }

    /********************  测试的依赖关系 start *******************/
    public function testInitStack()
    {
        $this->service->init();
        $this->assertEquals(3, $this->service->getStackSize());

        return $this->service;
    }

    /**
     * @depends testInitStack
     * @param TestService $service
     */
    public function testStackContains(TestService $service)
    {
        $contains = $service->stackContains('学院君');
        $this->assertTrue($contains);
    }
    /********************  测试的依赖关系 end *******************/

    public function initDataProvider()
    {
        return [
            ['学院君'],
            ['Laravel学院']
        ];
    }

    /**
     * @depends testInitStack
     * @dataProvider initDataProvider
     */
    public function testIsStackContains()
    {
        $arguments = func_get_args();
        $service = $arguments[1];
        $value = $arguments[0];
        $this->assertTrue($service->stackContains($value));
    }

}
