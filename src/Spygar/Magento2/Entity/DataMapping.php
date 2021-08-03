<?php

namespace Spygar\Magento2\Entity;

/**
 * DataMapping
 */
class DataMapping
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $entityType;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $externalId;

    /**
     * @var string|null
     */
    private $relatedId;

    /**
     * @var string
     */
    private $shopUrl;

    /**
     * @var \DateTime|null
     */
    private $created;


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
     * Set entityType.
     *
     * @param string $entityType
     *
     * @return DataMapping
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * Get entityType.
     *
     * @return string
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return DataMapping
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set externalId.
     *
     * @param string $externalId
     *
     * @return DataMapping
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Get externalId.
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * Set relatedId.
     *
     * @param string|null $relatedId
     *
     * @return DataMapping
     */
    public function setRelatedId($relatedId = null)
    {
        $this->relatedId = $relatedId;

        return $this;
    }

    /**
     * Get relatedId.
     *
     * @return string|null
     */
    public function getRelatedId()
    {
        return $this->relatedId;
    }

    /**
     * Set shopUrl.
     *
     * @param string $shopUrl
     *
     * @return DataMapping
     */
    public function setShopUrl($shopUrl)
    {
        $this->shopUrl = $shopUrl;

        return $this;
    }

    /**
     * Get shopUrl.
     *
     * @return string
     */
    public function getShopUrl()
    {
        return $this->shopUrl;
    }

    /**
     * Set created.
     *
     * @param \DateTime|null $created
     *
     * @return DataMapping
     */
    public function setCreated($created = null)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime|null
     */
    public function getCreated()
    {
        return $this->created;
    }
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        // Add your code here
    }
}
