<?php

namespace xurl\parts;

use xurl\parts\exceptions\HostException;

/**
 * Class Host
 *
 * @package xurl\parts
 */
class Host extends AbstractUrlPart
{
    /**
     * @var string
     */
    protected $topLevelDomain;

    /**
     * @var string Part of url after top level domain
     */
    protected $subDomain;

    /**
     * @var string Url host value
     */
    protected $host;

    /**
     * The pattern for a fragments of host. This pattern does not allow to get
     * complete information about the host
     *
     * @var string
     */
    protected $pattern = '/^([\d\w]+[\d\w\-\.]*[\d\w]*)\.([\d\w]+)$/i';

    /**
     * Host constructor.
     *
     * @param null $host
     */
    public function __construct($host = null)
    {
        if ($host) {
            $this->setHost($host);
        }
    }

    /**
     * Set a new value for the host. As a parameter can take a string containing
     * a hostname or IP version 4 or 6.
     *
     * Example:
     * $host->setHost('example.com.uk')
     * $host->setHost('216.58.209.174')
     * $host->setHost('2001:0db8:11a3:09d7:1f34:8a2e:07a0:765d')
     *
     * @param string $host
     *
     * @return $this
     * @throws HostException
     */
    public function setHost($host)
    {
        $preparedHost = $host;

        if (!$this->validateHost($host)) {
            throw new HostException('Host must be not empty string.');
        }

        if ($this->setFromIp($preparedHost, false)) {
            return $this;
        }

        $topLevelDomain = null;

        if (preg_match($this->pattern, $preparedHost, $result)) {
            $this->topLevelDomain = $result[count($result) - 1];

            $this->subDomain = $result[1];

            $this->host = $result[0];
        } else {
            throw new HostException('Incorrect host format.');
        }

        return $this;
    }

    /**
     * Set a new value for the host from IP version 4 or 6.
     *
     * Example:
     * $host->setHost('216.58.209.174')
     * $host->setHost('2001:0db8:11a3:09d7:1f34:8a2e:07a0:765d')
     *
     * @param string $preparedHost
     * @param bool   $throwException
     *
     * @return bool
     * @throws HostException
     */
    public function setFromIp($preparedHost, $throwException = true)
    {
        $preparedHost = trim($preparedHost, '[]');

        if (filter_var($preparedHost, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $this->host = $preparedHost;

            return true;
        }

        if (filter_var($preparedHost, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {

            $this->host = '[' . $preparedHost . ']';

            return true;
        }

        if ($throwException) {
            throw new HostException('Incorrect ip format.');
        }

        return false;
    }

    /**
     * Check for correct host value.
     *
     * @param $preparedHost
     *
     * @return bool
     */
    protected function validateHost($preparedHost)
    {
        if (
            !$preparedHost
            || !is_string($preparedHost)
            || strlen($preparedHost) > 1000
        ) {
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getSubDomain()
    {
        return $this->subDomain;
    }

    /**
     * Get url host value.
     *
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getTopLevelDomain()
    {
        return $this->topLevelDomain;
    }

    /**
     * Return string value of host.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getHost();
    }
}