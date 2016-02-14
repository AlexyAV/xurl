<?php

namespace xurl\test\src\parts;

use xurl\parts\exceptions\SchemeException;
use xurl\parts\Scheme;

class SchemeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Scheme
     */
    protected $scheme;

    public function setUp()
    {
        $this->scheme = new Scheme();
    }

    public function schemeDataProvider()
    {
        return [
            ['http'], ['https'], ['data']
        ];
    }

    /**
     * @dataProvider schemeDataProvider
     *
     * @param $scheme
     */
    public function testSetScheme($scheme)
    {
        $this->scheme->setScheme($scheme);

        $this->assertEquals(
            $scheme, $this->scheme->getScheme(true)
        );

        $this->assertEquals(
            $scheme . '://', $this->scheme->getScheme()
        );
    }

    public function testSetSchemeException()
    {
        $this->setExpectedException(
            SchemeException::class, 'Unknown scheme type.'
        );

        $this->scheme->setScheme('incorrect');
    }

    public function testSetSchemeExceptionType()
    {
        $this->setExpectedExceptionRegExp(
            SchemeException::class, '/^Url scheme must be a string\.*./'
        );

        $this->scheme->setScheme(['http']);
    }

    public function testSetSchemeExceptionFormat()
    {
        $this->setExpectedExceptionRegExp(
            SchemeException::class, '/^Invalid scheme format.\.*./'
        );

        $this->scheme->setScheme('ht@ps');
    }

    public function testGetSchemeList()
    {
        $schemeList = [
            'ftp', 'http', 'https', 'mailto', 'irc', 'file', 'data',
        ];

        $this->assertEquals($schemeList, $this->scheme->getSchemeList());
    }

    public function testDefaultScheme()
    {
        $this->assertEquals(
            Scheme::DEFAULT_SCHEME, $this->scheme->getScheme(true)
        );
    }

    public function testToString()
    {
        $this->assertEquals(
            Scheme::DEFAULT_SCHEME . '://', (string)$this->scheme
        );
    }
}