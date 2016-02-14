<?php

namespace xurl\test\src\parts;

use xurl\parts\exceptions\HostException;
use xurl\parts\Host;

class HostTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Host
     */
    protected $host;

    public function setUp()
    {
        $this->host = new Host;
    }

    public function hostDataProvider()
    {
        return [
            ['example.com'],
            ['example.com.uk'],
            ['my-example.and.you-example.com.uk'],
            ['216.58.209.174'],
        ];
    }

    public function testSetFromIp()
    {
        $ipv4 = '216.58.209.174';

        $ipv6 = '2001:0db8:11a3:09d7:1f34:8a2e:07a0:765d';

        $this->host->setFromIp($ipv4);

        $this->assertEquals($ipv4, $this->host->getHost());

        $this->host->setFromIp($ipv6);

        $this->assertEquals('[' . $ipv6 . ']', $this->host->getHost());
    }

    /**
     * @dataProvider hostDataProvider
     * @param $host
     */
    public function testSetHost($host)
    {
        $this->host->setHost($host);

        $this->assertEquals($host, $this->host->getHost());
    }

    public function testSetHostExceptionType()
    {
        $this->setExpectedException(
            HostException::class, 'Host must be not empty string.'
        );

        $this->host->setHost(['example']);
    }

    public function testSetHostExceptionFormat()
    {
        $this->setExpectedException(
            HostException::class, 'Incorrect host format.'
        );

        $this->host->setHost('example');
    }

    public function testSetFromIpException()
    {
        $this->setExpectedException(
            HostException::class, 'Incorrect ip format.'
        );

        $this->host->setFromIp('example.com');
    }

    public function testGetTopLevelDomain()
    {
        $this->host->setHost('example.com');

        $this->assertEquals('com', $this->host->getTopLevelDomain());
    }

    public function testGetSubDomain()
    {
        $this->host->setHost('example.com');

        $this->assertEquals('example', $this->host->getSubDomain());
    }

    /**
     * @dataProvider hostDataProvider
     * @param $host
     */
    public function testToString($host)
    {
        $this->host->setHost($host);

        $this->assertEquals($host, (string)$this->host);
    }
}