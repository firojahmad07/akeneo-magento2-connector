<?php

namespace Spygar\Magento2\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Spygar\Magento2\Entity\DataMapping;

/**
 * DataMappingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DataMappingRepository extends \Doctrine\ORM\EntityRepository
{
     /** @var EntityManagerInterface */
     protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $classMeta = $this->entityManager->getClassMetadata(DataMapping::class);
        parent::__construct($this->entityManager, $classMeta);
    }

    /** create or update mapping */
    public function createOrUpdateMapping($params, $mapping)
    {
        $dateTime = new \DateTime();
        $dataMapping = !empty($mapping) ? $mapping : new DataMapping;
        
        $dataMapping->setCode($params['code']);
        $dataMapping->setEntityType($params['entityType']);
        $dataMapping->setExternalId($params['externalId']);
        $dataMapping->setRelatedId($params['relatedId']);
        $dataMapping->setUrl($params['url']);
        $dataMapping->setCreated($dateTime);

        $this->entityManager->persist($dataMapping);
        $this->entityManager->flush();
    }
}
