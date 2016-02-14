<?php

namespace xurl\test\src\parts;

use xurl\parts\exceptions\UserInfoException;
use xurl\parts\UserInfo;

class UserInfoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserInfo
     */
    protected $userInfo;

    public function setUp()
    {
        $this->userInfo = new UserInfo;
    }

    public function userInfoDataProvider()
    {
        return [
            ['user:password', 'user:password@', 'user', 'password'],
            [['user', 'password'], 'user:password@', 'user', 'password'],
            [['user'], 'user@', 'user', null],
            ['user', 'user@', 'user', null],
        ];
    }

    /**
     * @dataProvider userInfoDataProvider
     *
     * @param $userInfo
     * @param $expected
     */
    public function testSetUserInfo($userInfo, $expected)
    {
        $this->userInfo->setUserInfo($userInfo);

        $this->assertEquals($expected, $this->userInfo->getUserInfo());
    }

    /**
     * @dataProvider userInfoDataProvider
     *
     * @param $userInfo
     * @param $expected
     * @param $user
     * @param $password
     */
    public function testGetUserNameAndPassword(
        $userInfo, $expected, $user, $password
    ) {
        $this->userInfo->setUserInfo($userInfo);

        $this->assertEquals($user, $this->userInfo->getUserName());

        $this->assertEquals($password, $this->userInfo->getPassword());
    }

    /**
     * @dataProvider userInfoDataProvider
     *
     * @param $userInfo
     * @param $expected
     * @param $user
     * @param $password
     *
     * @internal     param $query
     */
    public function testToString($userInfo, $expected, $user, $password)
    {
        $this->userInfo->setUserInfo($userInfo);

        $this->assertEquals($expected, (string) $this->userInfo);
    }

    public function testSetUserNameExceptionType()
    {
        $this->setExpectedException(UserInfoException::class);

        $this->userInfo->setUserName(['user']);
    }

    public function testSetPasswordExceptionType()
    {
        $this->setExpectedException(UserInfoException::class);

        $this->userInfo->setPassword(['password']);
    }

    public function testSetUserInfoExceptionEmpty()
    {
        $this->setExpectedException(UserInfoException::class, 'Empty user info.');

        $this->userInfo->setUserInfo([]);
    }

    public function testSetUserInfoExceptionType()
    {
        $this->setExpectedExceptionRegExp(
            UserInfoException::class, '/Invalid user info value.*/'
        );

        $this->userInfo->setUserInfo(new \stdClass);
    }

    public function testSetUserInfoFromArrayExceptionType()
    {
        $this->setExpectedExceptionRegExp(
            UserInfoException::class,
            '/Url user name and password be a string or integer.*/'
        );

        $this->userInfo->setUserInfo(['user', []]);
    }
}