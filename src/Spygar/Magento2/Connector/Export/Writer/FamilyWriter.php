<?php

namespace Spygar\Magento2\Connector\Export\Writer;

use Spygar\Magento2\Traits\DataMappingTrait;
use Akeneo\Tool\Component\Batch\Item\ItemWriterInterface;

/**
 * Export Family as attribute set to Magento2
 *
 * @author Spygar
 */

class FamilyWriter extends BaseWriter implements ItemWriterInterface
{
    use DataMappingTrait;

    const AKENEO_ENTITY_NAME   = 'category';
    const ACTION_ADD_OR_UPDATE = 'addOrUpdate';
    const RESOURCE_WRAPPER     = 'category';

    protected $magento2Url;
    /**
     * Write Categories to Magento2   
     */
    
    public function write(array $items)
    {
        dump("families : ",  $items);die;
        // $jobCredential      = $this->connectorService->getJobCredentialData();
        // $this->magento2Url  = $jobCredential['url'];
        // foreach ($items as $item) {
        //     foreach($jobCredential['storeViewMapping'] as  $storeViewMapping) 
        //     {   
        //         $mapping = $this->checkMappingInDb($item, $this->magento2Url);
        //         $formattedData = $this->formatData($item, $mapping, $storeViewMapping['locale']);
                
        //         $result = $this->connectorService->requestApiAction(
        //             $jobCredential,
        //             self::ACTION_ADD_OR_UPDATE,
        //             $formattedData,
        //             $storeViewMapping['store']
        //         );
                
        //         if($result['status'] == 200) {
        //             $resultData =['externalId' => $result['data']['id'], 'relatedId'  => $result['data']['parent_id'] ];
        //             $this->handleApiRequest($item, $resultData, $mapping, $this->magento2Url);
        //         }

        //         // $this->stepExecution->addWarning('Warning : ', [], 
        //         //     new DataInvalidItem([$storeViewMapping['store']. 
        //         //     '']));
        //     }

        //     /* increment write count */
        //     $this->stepExecution->incrementSummaryInfo('write');
        // }
    }
    /** format category data */
    protected function formatData($item, $mapping, $locale)
    {
        $labels = !empty($item['labels']) ? $item['labels'] : [];
        $formatted = [
            'id' => !empty($mapping) ? $mapping->getExternalId() : 0,
            'parent_id' => $this->getCategoryParentId($item, $mapping),
            'is_active' => true,
            'include_in_menu' => true,
            'name' => !empty($labels[$locale]) ?  $labels[$locale] : $item['code'],
         
        ];

        return [ self::RESOURCE_WRAPPER => $formatted ];
    }

    public function getCategoryParentId($item, $mapping)
    {
        $parentId = 1;
        if(!empty($item['parent'])) {
            $parentMapping  = $this->checkMappingInDb(['code' => $item['parent']], $this->magento2Url);
            $parentId = !empty($parentMapping) ? $parentMapping->getExternalId() : $parentId;
        }

        return $parentId;
    }
}
