<?php

namespace Tests\Feature\APIs\V1;

use Zhiyi\Plus\Models\User;

class AuthLoginTest extends TestCase
{
    protected static $uri = '/api/v1/auth';

    protected static $user;

    protected static $phone;
    protected static $name;
    protected static $password;

    /**
     * 前置操作,注册用户.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function setUp()
    {
        parent::setUp();

        static::$phone = '187819'.rand(11111, 99999);
        static::$name = 'ts'.rand(10000, 99999);
        static::$password = '123456';

        static::$user = new User();
        static::$user->phone = static::$phone;
        static::$user->name = static::$name;
        static::$user->password = bcrypt(static::$password);
        static::$user->save();
    }

    /**
     * 卸载方法，清理测试后的冗余数据.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    protected function tearDown()
    {
        static::$user->forceDelete();
        parent::tearDown();
    }

    /**
     * 测试无设备号的登录情况.
     * code: 1014.
     *
     * @return [type] [description]
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function testNotExisteDeviceCode()
    {
        $requestBody = [
            'phone'    => static::$phone,
            'password' => static::$password,
        ];

        $response = $this->postJson(static::$uri, $requestBody);

        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(422);

        // Assert that the response contains an exact JSON array.
        $json = static::createJsonData([
            'code'    => 1014,
            'message' => '设备号不能为空',
        ]);

        $response->assertJson($json);
    }

    /**
     * 测试登录无phone字段情况.
     * code: 1000.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function testNotExistePhone()
    {
        // request.
        $response = $this->postJson(static::$uri, [
            'pasword'     => static::$password,
            'device_code' => 'The is device code.',
        ]);

        // Asserts that the status code of the response matches the given code.
        $response->assertStatus(403);

        // Assert that the response contains an exact JSON array.
        $json = static::createJsonData([
            'code' => 1000,
        ]);
        $response->assertJson($json);
    }

    /**
     * 测试无登录密码情况下的错误情况.
     * code: 1006.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function testNotExistePassword()
    {
        // request.
        $response = $this->postJson(static::$uri, [
            'phone'       => static::$phone,
            'device_code' => 'The is device code.',
        ]);

        // Assert that the status code of the response matches the giben code.
        $response->assertStatus(401);

        // Assert that the response contains an exact JSON array.
        $json = static::createJsonData([
            'code' => 1006,
        ]);
        $response->assertJson($json);
    }

    /**
     * 测试用户不存在.
     * code: 1005.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function testNotFundUser()
    {
        $response = $this->postJson(static::$uri, [
            'phone'       => '18781932642',
            'password'    => static::$password,
            'device_code' => 'The is device code.',
        ]);

        // Assert that the status code of the response matches the giben code.
        $response->assertStatus(404);

        // Assert that the response contains an exact JSON array.
        $json = static::createJsonData([
            'code' => 1005,
        ]);
        $response->assertJson($json);
    }

    /**
     * 测试密码错误情况.
     * code: 1006.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function testErrorPassword()
    {
        $response = $this->postJson(static::$uri, [
            'phone'       => static::$phone,
            'password'    => 'xxx',
            'device_code' => 'The is device code.',
        ]);

        // Assert that the status code of the response matches the giben code.
        $response->assertStatus(401);

        // Assert that the response contains an exact JSON array.
        $json = static::createJsonData([
            'code' => 1006,
        ]);
        $response->assertJson($json);
    }

    /**
     * 测试用户登录成功.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function testLoginSuccess()
    {
        $response = $this->postJson(static::$uri, [
            'phone'       => static::$phone,
            'password'    => static::$password,
            'device_code' => 'The is device code.',
        ]);

        // Assert that the status code of the response matches the giben code.
        $response->assertStatus(201);
        // more, 201 status code is successful.
    }
}
