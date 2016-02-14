<?php

namespace xurl\test\src\parts;

use xurl\parts\exceptions\QueryException;
use xurl\parts\Query;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Query
     */
    protected $query;

    public function setUp()
    {
        $this->query = new Query;
    }

    public function queryDataProvider()
    {
        return [
            ['key = val', '?key=val'],
            [['key' => 'val'], '?key=val'],
            ['val', '?val'],
            [
                [
                    'key' => 'val',
                    'parent' => ['key1' => 'val1']
                ],
                '?key=val&parent%5Bkey1%5D=val1'
            ],
        ];
    }

    /**
     * @dataProvider queryDataProvider
     *
     * @param $query
     * @param $expected
     */
    public function testSetQuery($query, $expected)
    {
        $this->query->setQuery($query);

        $this->assertEquals($expected, $this->query->getQuery());
    }

    public function testGetQueryParams()
    {
        $this->query->setQuery(['key' => 'val']);

        $this->assertEquals('val', $this->query->getQueryParams()['key']);
    }

    public function testSetSchemeExceptionEmpty()
    {
        $this->setExpectedException(QueryException::class, 'Empty query.');

        $this->query->setQuery(' ');
    }

    public function testSetSchemeExceptionType()
    {
        $this->setExpectedExceptionRegExp(
            QueryException::class, '/Invalid query param.*/'
        );

        $this->query->setQuery(13);
    }

    /**
     * @dataProvider queryDataProvider
     *
     * @param $query
     * @param $expected
     */
    public function testToString($query, $expected)
    {
        $this->query->setQuery($query);

        $this->assertEquals($expected, (string) $this->query);
    }
}