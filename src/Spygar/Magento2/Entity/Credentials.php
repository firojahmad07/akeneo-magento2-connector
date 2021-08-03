<?php

namespace Spygar\Magento2\Entity;

/**
 * Credentials
 */
class Credentials
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $version;

    /**
     * @var bool
     */
    private $active = 0;

    /**
     * @var bool
     */
    private $defaultSet = 0;

    /**
     * @var string|null
     */
    private $resources;

    /**
     * @var string|null
     */
    private $extras;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return Credentials
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set apiKey.
     *
     * @param string $apiKey
     *
     * @return Credentials
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return Credentials
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set version.
     *
     * @param string $version
     *
     * @return Credentials
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Credentials
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set defaultSet.
     *
     * @param bool $defaultSet
     *
     * @return Credentials
     */
    public function setDefaultSet($defaultSet)
    {
        $this->defaultSet = $defaultSet;

        return $this;
    }

    /**
     * Get defaultSet.
     *
     * @return bool
     */
    public function getDefaultSet()
    {
        return $this->defaultSet;
    }

    /**
     * Set resources.
     *
     * @param string|null $resources
     *
     * @return Credentials
     */
    public function setResources($resources = null)
    {
        $this->resources = $resources;

        return $this;
    }

    /**
     * Get resources.
     *
     * @return string|null
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Set extras.
     *
     * @param string|null $extras
     *
     * @return Credentials
     */
    public function setExtras($extras = null)
    {
        $this->extras = $extras;

        return $this;
    }

    /**
     * Get extras.
     *
     * @return string|null
     */
    public function getExtras()
    {
        return $this->extras;
    }
}
