<?php
namespace Spygar\Magento2\Traits;

/**
 * Manage all mapping here.
 */
trait DataMappingTrait
{
    /** check mapping tinto database with parameters */
    public function checkMappingInDb($item, $magento2Url)
    {
        $params = [
            'code'       => $item['code'],
            'url'        => $magento2Url,
            'entityType' => self::AKENEO_ENTITY_NAME
        ];

        return $this->connectorService->getMappingData($params);
    }

    /** handle api result and create mapping */
    public function handleApiRequest($item, $result, $mapping, $magento2Url)
    {
        $params = [
            'code'       => $item['code'],
            'url'        => $magento2Url,
            'entityType' => self::AKENEO_ENTITY_NAME,
            'externalId' => $result['externalId'],
            'relatedId'  => $result['relatedId']
        ];

        $this->connectorService->createOrUpdateMapping($params, $mapping);
    }
}
