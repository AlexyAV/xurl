<?php

namespace xurl;

use xurl\url\BaseUrl;

/**
 * Interface UrlCreatorInterface
 *
 * @package xurl
 */
interface UrlCreatorInterface
{
    /**
     * @param string $scheme
     *
     * @return object
     */
    public function scheme($scheme);

    /**
     * @param string $host
     *
     * @return object
     */
    public function host($host);

    /**
     * @param string|array $userInfo
     *
     * @return object
     */
    public function userInfo($userInfo);

    /**
     * @param string|array $path
     *
     * @return object
     */
    public function path($path);

    /**
     * @param string|array $query
     *
     * @return object
     */
    public function query($query);

    /**
     * @param string $fragment
     *
     * @return object
     */
    public function fragment($fragment);

    /**
     * @return BaseUrl
     */
    public function getUrl();

    /**
     * @return $this
     */
    public function createUrl();
}