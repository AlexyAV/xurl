<?php

namespace xurl\test\src\parts;

use xurl\parts\exceptions\FragmentException;
use xurl\parts\Fragment;

class FragmentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Fragment
     */
    protected $fragment;

    public function setUp()
    {
        $this->fragment = new Fragment;
    }

    public function fragmentDataProvider()
    {
        return [
            ['row'],
            ['row=4'],
            ['row=5-7'],
            ['cell=4,1-6,2'],
            ['t=40,80&xywh=160,120,320,240'],
        ];
    }

    /**
     * @dataProvider fragmentDataProvider
     * @param $fragment
     */
    public function testSetFragment($fragment)
    {
        $this->fragment->setFragment($fragment);

        $this->assertEquals('#' . $fragment, $this->fragment->getFragment());
    }

    public function testSetFragmentExceptionType()
    {
        $this->setExpectedException(FragmentException::class);

        $this->fragment->setFragment([]);
    }

    public function testSetFragmentExceptionEmpty()
    {
        $this->setExpectedException(
            FragmentException::class, 'Empty fragment.'
        );

        $this->fragment->setFragment('');
    }

    /**
     * @dataProvider fragmentDataProvider
     * @param $fragment
     */
    public function testToString($fragment)
    {
        $this->fragment->setFragment($fragment);

        $this->assertEquals('#' . $fragment, (string)$this->fragment);
    }
}