<?php

namespace xurl\parts;
use xurl\parts\exceptions\PortException;

/**
 * Class Port
 *
 * @package xurl
 */
class Port extends AbstractUrlPart
{
    const DEFAULT_PORT = 80;

    /**
     * @var int
     */
    private $_port;

    /**
     * Port constructor.
     *
     * @param null $port
     */
    public function __construct($port = null)
    {
        if ($port) {
            $this->setPort($port);
        }
    }

    /**
     * @param string|int $port
     * @param bool       $useDefaultPort
     *
     * @return $this
     * @throws PortException
     */
    public function setPort($port, $useDefaultPort = false)
    {
        $preparedPort = $port;

        if (!is_string($preparedPort) && !is_numeric($preparedPort)) {
            throw new PortException(
                'Url port must be a string or integer. ' .
                gettype($port) . ' passed.'
            );
        }

        if (is_string($port)) {
            $preparedPort = (int) trim($port);
        }

        if ($useDefaultPort) {
            $this->_port = self::DEFAULT_PORT;

            return $this;
        }

        if (!preg_match('/^\d{0,5}$/', $port) || (int)$port > 65535) {
            throw new PortException(
                'Invalid port format. Expected value from 0 to 65535.'
            );
        }

        $preparedPort = filter_var($port, FILTER_SANITIZE_NUMBER_INT);

        $this->_port = $preparedPort;

        return $this;
    }

    /**
     * @param bool $raw
     *
     * @return string
     */
    public function getPort($raw = false)
    {
        if ($raw) {
            return $this->_port;
        }

        if ($this->_port == self::DEFAULT_PORT) {
            return '';
        }

        return ':' . $this->_port;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getPort();
    }
}