<?php

namespace xurl\url;

use xurl\parts\Fragment;
use xurl\parts\Host;
use xurl\parts\Path;
use xurl\parts\Port;
use xurl\parts\Query;
use xurl\parts\Scheme;
use xurl\parts\UserInfo;

/**
 * Class Url
 *
 * @package xurl
 */
class Url extends BaseUrl
{
    /**
     * @var Scheme
     */
    public $scheme;

    /**
     * @var UserInfo
     */
    public $userInfo;

    /**
     * @var Host
     */
    public $host;

    /**
     * @var Port
     */
    public $port;

    /**
     * @var Path
     */
    public $path;

    /**
     * @var Query
     */
    public $query;

    /**
     * @var Fragment
     */
    public $fragment;

    /**
     * @return Scheme
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @return UserInfo
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * @return Host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return Port
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return Path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return Fragment
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * @param Scheme $scheme
     */
    public function setScheme(Scheme $scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * @param UserInfo $userInfo
     */
    public function setUserInfo(UserInfo $userInfo)
    {
        $this->userInfo = $userInfo;
    }

    /**
     * @param Host $host
     */
    public function setHost(Host $host)
    {
        $this->host = $host;
    }

    /**
     * @param Port $port
     */
    public function setPort(Port $port)
    {
        $this->port = $port;
    }

    /**
     * @param Path $path
     */
    public function setPath(Path $path)
    {
        $this->path = $path;
    }

    /**
     * @param Query $query
     */
    public function setQuery(Query $query)
    {
        $this->query = $query;
    }

    /**
     * @param Fragment $fragment
     */
    public function setFragment(Fragment $fragment)
    {
        $this->fragment = $fragment;
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws UrlException
     */
    public function __get($name)
    {
        $methodName = 'get' . ucfirst($name);

        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        throw new UrlException(
            'Getting unknown property: ' . get_class($this) . '::' . $name
        );
    }

    /**
     * Creates a new URL using defined url parts. Returns absolute or relative
     * url depends on second parameter.
     *
     * @param bool $absolute
     *
     * @return string
     */
    public function getUrl($absolute = true)
    {
        $url = [];

        if ((bool) $absolute) {
            $url = [
                $this->scheme,
                $this->userInfo,
                $this->host,
                $this->port,
            ];
        }

        $url = array_merge($url, [
            $this->path,
            $this->query,
            $this->fragment
        ]);

        return ltrim(implode('', $url), '/');
    }
}