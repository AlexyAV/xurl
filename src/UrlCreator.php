<?php

namespace xurl;

use xurl\parts\Host;
use xurl\parts\Path;
use xurl\parts\Port;
use xurl\parts\Query;
use xurl\parts\Scheme;
use xurl\parts\UserInfo;
use xurl\parts\Fragment;
use xurl\url\Url;

/**
 * Class UrlCreator
 *
 * @package xurl
 */
class UrlCreator implements UrlCreatorInterface
{
    /**
     * @var Url
     */
    private $_url;

    /**
     * UrlCreator constructor.
     */
    public function __construct()
    {
        $this->_url = new Url;
    }

    /**
     * @param string $scheme
     * @param bool   $customScheme
     *
     * @return $this
     * @throws \Exception
     */
    public function scheme($scheme, $customScheme = false)
    {
        $this->_url->setScheme(
            (new Scheme())->setScheme($scheme, $customScheme)
        );

        return $this;
    }

    /**
     * @param string $userInfo
     *
     * @return $this
     * @throws \Exception
     */
    public function userInfo($userInfo)
    {
        $this->_url->setUserInfo(
            (new UserInfo())->setUserInfo($userInfo)
        );

        return $this;
    }

    /**
     * @param string $host
     *
     * @return $this
     * @throws \Exception
     */
    public function host($host)
    {
        $this->_url->setHost(
            (new Host())->setHost($host)
        );

        return $this;
    }

    /**
     * @param $port
     *
     * @return $this
     * @throws \Exception
     */
    public function port($port)
    {
        $this->_url->setPort(
            (new Port())->setPort($port)
        );

        return $this;
    }

    /**
     * @return Url
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @param array|string $path
     * @param bool         $replace
     *
     * @return $this
     * @throws \Exception
     */
    public function path($path, $replace = true)
    {
        if ($this->_url->path) {
            $this->_url->path->setPath($path, $replace);
        } else {

            $this->_url->setPath(
                (new Path())->setPath($path)
            );
        }

        return $this;
    }

    /**
     * @param array|string $query
     *
     * @return $this
     */
    public function query($query)
    {
        $this->_url->setQuery((new Query())->setQuery($query));

        return $this;
    }

    /**
     * @param string $fragment
     *
     * @return $this
     * @throws \Exception
     */
    public function fragment($fragment)
    {
        $this->_url->setFragment(
            (new Fragment())->setFragment($fragment)
        );

        return $this;
    }

    /**
     * Create new url using current.
     *
     * @return $this
     */
    public function current()
    {
        $currentUrl = $_SERVER['HTTP_HOST'] . ':' .$_SERVER['SERVER_PORT'];

        if (isset($_SERVER['REQUEST_URI'])) {
            $currentUrl .= $_SERVER['REQUEST_URI'];
        }

        $urlParts = parse_url($currentUrl);

        if (!isset($currentUrl['scheme'])) {
            $this->getUrl()->setScheme(new Scheme());
        }

        if (isset($currentUrl['user'])) {
            $this->userInfo(
                [
                    $currentUrl['user'],
                    isset($currentUrl['pass']) ? $currentUrl['pass'] : ''
                ]
            );
        }

        foreach ($urlParts as $partName => $value) {
            if (!method_exists($this, $partName)) {
                continue;
            }

            $this->$partName($value);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function createUrl()
    {
        return $this->_url->getUrl();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->createUrl();
    }
}