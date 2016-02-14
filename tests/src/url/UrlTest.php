<?php

namespace xurl\test\src;

use xurl\parts\Fragment;
use xurl\parts\Host;
use xurl\parts\Path;
use xurl\parts\Port;
use xurl\parts\Query;
use xurl\parts\Scheme;
use xurl\parts\UserInfo;
use xurl\url\Url;
use xurl\url\UrlException;

class UrlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Url
     */
    protected $url;

    public function setUp()
    {
        $this->url = new Url;
    }

    public function testSetScheme()
    {
        $this->url->setScheme($this->getUrlPartMock(Scheme::class));

        $this->assertInstanceOf(Scheme::class, $this->url->getscheme());

        $this->assertInstanceOf(Scheme::class, $this->url->scheme);
    }

    public function testSetUserInfo()
    {
        $this->url->setUserInfo($this->getUrlPartMock(UserInfo::class));

        $this->assertInstanceOf(UserInfo::class, $this->url->getUserInfo());

        $this->assertInstanceOf(UserInfo::class, $this->url->userInfo);
    }

    public function testSetHost()
    {
        $this->url->setHost($this->getUrlPartMock(Host::class));

        $this->assertInstanceOf(Host::class, $this->url->getHost());

        $this->assertInstanceOf(Host::class, $this->url->host);
    }

    public function testSetPort()
    {
        $this->url->setPort($this->getUrlPartMock(Port::class));

        $this->assertInstanceOf(Port::class, $this->url->getPort());

        $this->assertInstanceOf(Port::class, $this->url->port);
    }

    public function testSetPath()
    {
        $this->url->setPath($this->getUrlPartMock(Path::class));

        $this->assertInstanceOf(Path::class, $this->url->getPath());

        $this->assertInstanceOf(Path::class, $this->url->path);
    }

    public function testSetQuery()
    {
        $this->url->setQuery($this->getUrlPartMock(Query::class));

        $this->assertInstanceOf(Query::class, $this->url->getQuery());

        $this->assertInstanceOf(Query::class, $this->url->query);
    }

    public function testSetFragment()
    {
        $this->url->setFragment($this->getUrlPartMock(Fragment::class));

        $this->assertInstanceOf(Fragment::class, $this->url->getFragment());

        $this->assertInstanceOf(Fragment::class, $this->url->fragment);
    }

    public function testGetUrlPartException()
    {
        $this->setExpectedException(UrlException::class);

        $this->url->property;
    }

    public function testGetUrl()
    {
        $this->url->setScheme($this->getUrlPartMock(Scheme::class, 'https://'));

        $this->url->setUserInfo(
            $this->getUrlPartMock(UserInfo::class, 'root:pass@')
        );

        $this->url->setHost($this->getUrlPartMock(Host::class, 'example.com'));

        $this->url->setPort($this->getUrlPartMock(Port::class, ':8080'));

        $this->url->setPath($this->getUrlPartMock(Path::class, '/test/path/'));

        $this->url->setQuery(
            $this->getUrlPartMock(Query::class, '?key1=val1&key2=val2')
        );

        $this->url->setFragment(
            $this->getUrlPartMock(Fragment::class, '#fragment')
        );

        $this->assertEquals(
            'https://root:pass@example.com:8080/test/path/?key1=val1&key2=val2#fragment',
            $this->url->getUrl()
        );
    }

    /**
     * @param      $className
     * @param null $returnValue
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Fragment|Host|Path|Port|Query|Scheme|UserInfo|Url
     */
    protected function getUrlPartMock($className, $returnValue = null)
    {
        $mock = $this->getMockBuilder($className)
            ->getMock();

        if ($returnValue) {
            $mock->method('__toString')->willReturn($returnValue);
        }

        return $mock;
    }
}