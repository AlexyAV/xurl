<?php

namespace xurl\parts;

use xurl\parts\exceptions\UserInfoException;

/**
 * Class UserInfo
 *
 * @package xurl\parts
 */
class UserInfo extends AbstractUrlPart
{
    /**
     * @var string Url user info
     */
    protected $userInfo;

    /**
     * @var string Non encoded or sanitized user info
     */
    protected $plainUserInfo;

    /**
     * @var string User value
     */
    protected $user;

    /**
     * @var string Password value
     */
    protected $password;

    /**
     * UserInfo constructor.
     *
     * @param null $userInfo
     */
    public function __construct($userInfo = null)
    {
        if ($userInfo) {
            $this->setUserInfo($userInfo);
        }
    }

    /**
     * Set new url user info. It can accept a string or a array values. Password
     * value is optional.
     *
     * Example:
     * $userInfo->setUserInfo('user:password')
     * $userInfo->setUserInfo(['user', 'password'])
     * $userInfo->setUserInfo(['user'])
     *
     * @param string|array $userInfo
     *
     * @return $this
     * @throws UserInfoException
     */
    public function setUserInfo($userInfo)
    {
        $this->validateUserInfo($userInfo);

        if (is_array($userInfo)) {
            $this->getUserInfoFromArray($userInfo);
        } else {
            $this->getUserInfoFromString($userInfo);
        }

        return $this;
    }

    /**
     * @param string $userName
     *
     * @return $this
     * @throws UserInfoException
     */
    public function setUserName($userName)
    {
        if (!is_string($userName) && !is_numeric($userName)) {
            throw new UserInfoException(
                'Url user name must be a string or integer. ' .
                gettype($userName) . ' passed.'
            );
        }
        $this->user = trim($userName);

        return $this;
    }

    /**
     * @param string $password
     *
     * @return $this
     * @throws UserInfoException
     */
    public function setPassword($password)
    {
        if (!is_string($password) && !is_numeric($password)) {
            throw new UserInfoException(
                'Url user password be a string or integer. ' .
                gettype($password) . ' passed.'
            );
        }
        $this->password = trim($password);

        return $this;
    }

    /**
     * @param string $userInfo
     * @throws UserInfoException
     */
    protected function validateUserInfo($userInfo)
    {
        if (is_string($userInfo)) {
            $userInfo = trim($userInfo);
        }

        if (!$userInfo) {
            throw new UserInfoException(
                'Empty user info.'
            );
        }

        if (!is_array($userInfo) && !is_string($userInfo)) {
            throw new UserInfoException(
                'Invalid user info value. Must be a string or array.' .
                gettype($userInfo) . ' passed.'
            );
        }
    }

    /**
     * @param $userInfo
     *
     * @return array
     */
    protected function getUserInfoFromArray($userInfo)
    {
        array_map(function($val) {
            if (!is_string($val) && !is_numeric($val)) {
                throw new UserInfoException(
                    'Url user name and password be a string or integer. ' .
                    gettype($val) . ' passed.'
                );
            }

        }, $userInfo);

        $this->setUserName($userInfo[0]);

        if (count($userInfo) > 1) {
            $this->setPassword($userInfo[1]);
        }
    }

    /**
     * @param $userInfo
     *
     * @return array
     * @throws UserInfoException
     */
    protected function getUserInfoFromString($userInfo)
    {
        if (!preg_match('/^(.+?)(?:\:(.+))*$/', $userInfo, $userData)) {
            throw new UserInfoException(
                'Incorrect user information string format. ' .
                'Must be "user:password" or "user".'
            );
        }

        return $this->getUserInfoFromArray(array_slice($userData, 1));
    }

    /**
     * @param bool $raw
     *
     * @return string
     */
    public function getUserInfo($raw = true)
    {
        if ($this->user && !$this->password) {
            return !$raw ? $this->user : rawurlencode($this->user) . '@';
        }

        $userInfo =  array_map(
            'rawurlencode', [$this->user, $this->password]
        );

        $userInfo = implode(':', $userInfo);

        return !$raw ? $userInfo : $userInfo . '@';
    }

    /**
     * @param bool $raw
     *
     * @return string
     */
    public function getUserName($raw = false)
    {
        return !$raw ? rawurldecode($this->user) : $this->user;
    }

    /**
     * @param bool $raw
     *
     * @return string
     */
    public function getPassword($raw = false)
    {
        return !$raw ? rawurldecode($this->password) : $this->password;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUserInfo();
    }
}