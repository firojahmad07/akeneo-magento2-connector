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
    private $accessToken;

    /**
     * @var bool
     */
    private $active = 0;

    /**
     * @var bool
     */
    private $defaultSet = 0;
    /**
     * @var json|null
     */
    private $resources;

    /**
     * @var json|null
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
     * Set accessToken.
     *
     * @param string $accessToken
     *
     * @return Credentials
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get accessToken.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
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
     * @param json|null $resources
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
     * @return json|null
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Set extras.
     *
     * @param json|null $extras
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
     * @return json|null
     */
    public function getExtras()
    {
        return $this->extras;
    }
}
