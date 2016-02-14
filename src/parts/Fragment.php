<?php

namespace xurl\parts;

use xurl\parts\exceptions\FragmentException;

/**
 * Class Fragment
 *
 * @package xurl\parts
 */
class Fragment extends AbstractUrlPart
{
    /**
     * @var string Url fragment value
     */
    protected $fragment;

    /**
     * Fragment constructor.
     *
     * @param string|null $fragment
     */
    public function __construct($fragment = null)
    {
        if ($fragment) {
            $this->setFragment($fragment);
        }
    }

    /**
     * Set a new value for the url fragment. It can accept a string or a number.
     *
     * Example:
     * $fragment->setFragment('row=5-7')
     * $fragment->setFragment('cell=4,1-6,2')
     *
     * @param string $fragment
     *
     * @return $this
     * @throws FragmentException
     */
    public function setFragment($fragment)
    {
        if (!is_string($fragment) && !is_numeric($fragment)) {
            throw new FragmentException(
                'Url fragment must be a string or integer. ' .
                gettype($fragment) . ' passed.'
            );
        }

        $preparedFragment = trim($fragment);

        if (!$preparedFragment) {
            throw new FragmentException(
                'Empty fragment.'
            );
        }

        $this->fragment = filter_var($preparedFragment, FILTER_SANITIZE_STRING);

        return $this;
    }

    /**
     * Get url prepared or raw fragment.
     *
     * @param bool $raw
     *
     * @return string
     */
    public function getFragment($raw = false)
    {
        return $raw ? $this->fragment : '#' . $this->fragment;
    }

    /**
     * Return string value of fragment.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getFragment();
    }
}