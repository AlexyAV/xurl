<?php

namespace xurl\parts;

use xurl\parts\exceptions\PathException;

/**
 * Class Path
 *
 * @package xurl\parts
 */
class Path extends AbstractUrlPart
{
    /**
     * @var array Array with path items 
     */
    protected $parts = [];

    /**
     * @var string Url path value
     */
    protected $path;

    /**
     * Path constructor.
     *
     * @param $path
     */
    public function __construct($path = null)
    {
        if ($path) {
            $this->setPath($path);
        }
    }

    /**
     * Set new url path. It can accept a string or a array values. If the second
     * parameter is set to true existing path will be replaced. This behavior
     * set by default.
     *
     * Example:
     * $path->setPath('test/path')
     * $path->setPath(['test', 'path', 13])
     *
     * @param string $path
     * @param bool   $replace
     *
     * @return $this
     * @throws PathException
     */
    public function setPath($path, $replace = true)
    {
        $this->validatePathValue($path);

        $preparedPath = $path;

        if (is_array($preparedPath)) {

            if (!$this->validateArrayPath($preparedPath)) {
                throw new PathException(
                    'Path array accepts only integer and string values.'
                );
            }
        } elseif (is_string($preparedPath)) {
            $preparedPath = $this->stringPathToArray($preparedPath);
        }

        $preparedPath = $this->sanitizeArrayPath($preparedPath);

        if (!$replace && $preparedPath) {
            $this->parts = array_merge($this->parts, $preparedPath);
        } else {
            $this->parts = $preparedPath ?: [];
        }

        $this->setActualPath();

        return $this;
    }


    protected function setActualPath()
    {
        $parts = array_map('rawurlencode', $this->parts);

        $this->path = $this->parts ? implode('/', $parts) : '';
    }

    /**
     * Check for valid path value.
     *
     * @param $path
     *
     * @throws PathException
     */
    protected function validatePathValue($path)
    {
        if (is_string($path)) {
            $path = trim($path);
        }

        if (!$path) {
            throw new PathException('Empty path.');
        }

        $preparedPath = $path;

        if (!is_array($preparedPath) && !is_string($preparedPath)) {
            throw new PathException(
                'Url path must be a string or array. ' .
                gettype($preparedPath) . ' passed.'
            );
        }
    }

    /**
     * Sanitize path item values.
     *
     * @param array $path
     *
     * @return array|bool
     */
    protected function sanitizeArrayPath(array $path)
    {
        array_walk($path, function(&$val) {
            if (is_string($val)) {
                $val = strtolower(trim($val));

                return;
            }

            if (is_int($val)) {
                $val = filter_var($val, FILTER_SANITIZE_NUMBER_INT);
            }
        });

        return $path;
    }

    /**
     * Check for all array of path items are string or integer values.
     *
     * @param array $path
     *
     * @return bool
     */
    public function validateArrayPath(array $path)
    {
        $valid = true;

        foreach ($path as $pathPart) {
            if (!is_int($pathPart) && !is_string($pathPart)) {

                $valid = false; break;
            }
        }

        return $valid;
    }

    /**
     * @param $path
     *
     * @return array|bool
     */
    protected function stringPathToArray($path)
    {
        if (!is_string($path)) {
            return false;
        }

        $pathParts = explode('/', trim($path, '/'));

        return $pathParts;
    }

    /**
     * @return array
     */
    public function getPathParts()
    {
        return $this->parts;
    }

    /**
     * Get url prepared or raw path.
     *
     * @param bool $raw
     *
     * @return string
     */
    public function getPath($raw = false)
    {
        return $raw ? $this->path : '/' . $this->path . '/';
    }

    /**
     * Return string value of path.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getPath();
    }
}