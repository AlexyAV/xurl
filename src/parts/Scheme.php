<?php

namespace xurl\parts;

use xurl\parts\exceptions\SchemeException;

/**
 * Class Scheme
 *
 * @package xurl
 */
class Scheme extends AbstractUrlPart
{
    const DEFAULT_SCHEME = 'http';

    /**
     * @var string Url scheme value
     */
    protected $scheme = self::DEFAULT_SCHEME;

    /**
     * @var array Accessible scheme types
     */
    protected $schemeList
        = [
            'ftp', 'http', 'https', 'mailto', 'irc', 'file', 'data',
        ];

    /**
     * Scheme constructor.
     *
     * @param null $scheme
     */
    public function __construct($scheme = null)
    {
        if ($scheme) {
            $this->setScheme($scheme);
        }
    }

    /**
     * Set new scheme value. If $customScheme parameter set to false scheme
     * value should be only one of $schemeList.
     *
     * @param string $scheme
     * @param bool $customScheme
     *
     * @return $this
     * @throws SchemeException
     */
    public function setScheme($scheme, $customScheme = false)
    {
        $this->validateScheme($scheme);

        $preparedScheme = trim($scheme);

        if (!$customScheme && !in_array($preparedScheme, $this->schemeList)) {
            throw new SchemeException('Unknown scheme type.');
        }

        $this->scheme = $preparedScheme;

        return $this;
    }

    /**
     * Check for valid scheme value.
     *
     * @param string $scheme
     *
     * @throws SchemeException
     */
    protected function validateScheme($scheme)
    {
        if (!is_string($scheme)) {
            throw new SchemeException(
                'Url scheme must be a string. ' . gettype($scheme) . ' passed.'
            );
        }

        if (!preg_match('/^\w+[\d\w\+\.-]*$/', $scheme)) {
            throw new SchemeException(
                'Invalid scheme format. Only letters, digits, plus, period' .
                ' and hyphen are allowed.'
            );
        }
    }

    /**
     * @return array
     */
    public function getSchemeList()
    {
        return $this->schemeList;
    }

    /**
     * @param bool $raw
     *
     * @return string
     */
    public function getScheme($raw = false)
    {
        return $raw ? $this->scheme : $this->scheme . '://';
    }

    /**
     * Return string value of scheme.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getScheme();
    }
}