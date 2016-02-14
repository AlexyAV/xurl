<?php

namespace xurl\test\src\parts;

use xurl\parts\exceptions\PathException;
use xurl\parts\Path;

class PathTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Path
     */
    protected $path;

    public function setUp()
    {
        $this->path = new Path;
    }

    public function pathDataProvider()
    {
        return [
            ['test', '/test/', ['test']],
            [['test', 'path', 13], '/test/path/13/', ['test', 'path', 13]],
            [['test path'], '/test%20path/', ['test path']],
            ['test/path', '/test/path/', ['test', 'path']],
        ];
    }

    /**
     * @dataProvider pathDataProvider
     *
     * @param $path
     * @param $expected
     */
    public function testSetPath($path, $expected)
    {
        $this->path->setPath($path);

        $this->assertEquals($expected, $this->path->getPath());
    }

    public function testAddPath()
    {
        $this->path->setPath('test');

        $this->assertEquals('/test/', $this->path->getPath());

        $this->path->setPath('path', false);

        $this->assertEquals('/test/path/', $this->path->getPath());
    }

    /**
     * @dataProvider pathDataProvider
     *
     * @param $path
     * @param $expected
     * @param $parts
     */
    public function testGetPathParts($path, $expected, $parts)
    {
        $this->path->setPath($path);

        $this->assertEquals($parts, $this->path->getPathParts());
    }

    public function testSetPathExceptionEmpty()
    {
        $this->setExpectedException(PathException::class, 'Empty path.');

        $this->path->setPath('  ');
    }

    public function testSetPathExceptionType()
    {
        $this->setExpectedExceptionRegExp(
            PathException::class, '/Url path must be a string or array.*/'
        );

        $this->path->setPath(13);
    }

    public function testSetPathArrayExceptionType()
    {
        $this->setExpectedExceptionRegExp(
            PathException::class, '/Path array accepts only.*/'
        );

        $this->path->setPath([['test']]);
    }

    /**
     * @dataProvider pathDataProvider
     *
     * @param $path
     * @param $expected
     */
    public function testToString($path, $expected)
    {
        $this->path->setPath($path);

        $this->assertEquals($expected, (string)$this->path);
    }
}