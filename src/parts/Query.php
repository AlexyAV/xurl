<?php

namespace xurl\parts;

use xurl\parts\exceptions\QueryException;

/**
 * Class Query
 *
 * @package xurl\parts
 */
class Query extends AbstractUrlPart
{
    /**
     * @var array Url query items
     */
    protected $query = [];

    /**
     * Query constructor.
     *
     * @param null $query
     */
    public function __construct($query = null)
    {
        if ($query) {
            $this->setQuery($query);
        }
    }

    /**
     * Set new url query parameters. It can accept a string or a array values.
     * If the second parameter is set to true existing path will be replaced.
     * This behavior set by default.
     *
     * Example:
     * $query->setQuery('key=val')
     * $query->setQuery(['key' => 'val','parent' => ['key1' => 'val1']])
     *
     * @param string|array $query
     * @param bool         $replace
     *
     * @return $this
     * @throws \Exception
     */
    public function setQuery($query, $replace = true)
    {
        $this->validateQuery($query);

        if ($replace) {
            $this->query = [];
        }

        if (is_array($query)) {
            $this->prepareQueryFromArray($query);
        } else {
            $this->prepareQueryFromString($query);
        }

        return $this;
    }

    /**
     * Check for valid query value.
     *
     * @param array|string $query
     *
     * @throws QueryException
     */
    protected function validateQuery($query)
    {
        if (is_string($query)) {
            $query = trim($query);
        }

        if (!$query) {
            throw new QueryException('Empty query.');
        }

        if (!is_array($query) && !is_string($query)) {
            throw new QueryException(
                'Invalid query param. Must be a string or array.'
            );
        }
    }

    /**
     * Set query parameters from array.
     *
     * @param array $query
     * @param null  $parentKey
     */
    protected function prepareQueryFromArray(array $query, $parentKey = null)
    {
        foreach ($query as $key => $value) {

            $key = trim($key);

            if (is_array($value)) {

                $preparedParentKey = $parentKey
                    ? "$parentKey" . "[$key]" : $key;

                $this->prepareQueryFromArray($value, $preparedParentKey);

                continue;
            }

            $key = $parentKey ? "$parentKey" . "[$key]" : $key;

            $this->query[$key] = $value;
        }
    }

    /**
     * Set query parameters from string.
     *
     * @param $query
     *
     * @return bool
     */
    protected function prepareQueryFromString($query)
    {
        $queryParams = explode('&', $query);

        // Split each query key-value pair
        array_map(function($value) {

            if (strpos($value, '=')) {
                list($key, $value) = explode('=', $value);
            } else {
                $key = $value;

                $value = '';
            }

            $this->query[trim($key)] = $value;

        }, $queryParams);

        return true;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    protected function encodeQueryParams($key, $value)
    {
        if (!$value) {
            return $key;
        }

        $queryParamData = array_map(function($val) {
            return urlencode(trim($val));
        }, [$key, $value]);

        return implode('=', $queryParamData);
    }

    /**
     * @return array
     */
    public function getQueryParams()
    {
        return $this->query;
    }

    /**
     * Get url prepared or raw query.
     *
     * @return string
     */
    public function getQuery()
    {
        if (!$this->query) {
            return '';
        }
        
        $preparedQueryParam = [];

        foreach ($this->query as $key => $value) {
            $preparedQueryParam[] = $this->encodeQueryParams($key, $value);
        }

        return '?' . implode('&', $preparedQueryParam);
    }

    /**
     * Return string value of query.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getQuery();
    }
}