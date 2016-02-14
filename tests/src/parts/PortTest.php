<?php

namespace xurl\test\src\parts;


use xurl\parts\exceptions\PortException;
use xurl\parts\Port;

class PortTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Port
     */
    protected $port;

    public function setUp()
    {
        $this->port = new Port;
    }

    public function portDataProvider()
    {
        return [
            [0],
            ['90'],
            [8080],
            ['65535']
        ];
    }

    /**
     * @dataProvider portDataProvider
     * @param $port
     */
    public function testSetPort($port)
    {
        $this->port->setPort($port);

        $this->assertEquals(':' . $port, $this->port->getPort());
    }

    public function testSetDefaultPort()
    {
        $this->port->setPort(80);

        $this->assertEquals('', $this->port->getPort());
    }

    public function testUseDefaultPort()
    {
        $this->port->setPort('', true);

        $this->assertEquals(80, $this->port->getPort(true));
    }

    public function testSetPortExceptionType()
    {
        $this->setExpectedExceptionRegExp(
            PortException::class, '/Url port must be a string or integer.*/'
        );

        $this->port->setPort([]);
    }

    public function testSetPortExceptionFormat()
    {
        $this->setExpectedExceptionRegExp(
            PortException::class, '/Invalid port format.*/'
        );

        $this->port->setPort('65536');
    }

    public function testToString()
    {
        $this->port->setPort(8080);

        $this->assertEquals(':8080', (string)$this->port);
    }
}