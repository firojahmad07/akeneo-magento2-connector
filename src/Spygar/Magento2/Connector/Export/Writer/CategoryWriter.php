<?php

namespace Spygar\Magento2\Connector\Export\Writer;

// use Spygar\Shopify\Entity\DataMapping;
// use Spygar\Shopify\Traits\DataMappingTrait;
use Akeneo\Tool\Component\Batch\Item\ItemWriterInterface;
// $obj = new \Spygar\ShopifyBundle\Listener\LoadingClassListener();
// $obj->checkVersionAndCreateClassAliases();

/**
 * Add categories to Magento2
 *
 * @author    Spygar
 * @copyright 2010-2017 Spygar pvt. ltd.
 * @license   https://store.Spygar.com/license.html
 */

class CategoryWriter extends BaseWriter implements ItemWriterInterface
{
    // use DataMappingTrait;

    // const AKENEO_ENTITY_NAME = 'category';
    // const SMART_COLLECTION_ENTITY = 'smart_collection';
    // const ACTION_ADD = 'addCategory';
    // const ACTION_UPDATE = 'updateCategory';

    // const CODE_ALREADY_EXIST = 'na';
    // const CODE_DUPLICATE_EXIST = 'na';
    // const CODE_UNPROCESSABLE = 422;

    // const CODE_NOT_EXIST = 404;
    // const RELATED_INDEX = '';

    // const RESOURCE_WRAPPER = 'custom_collection';

    /**
     * write products to ShopifyApi
     */
    public function write(array $items)
    {
        foreach ($items as $item) {
            if (empty($item['parent'])) {
                continue;
            }

            $mapping = $this->checkMappingInDb($item);
            if ($mapping) {
                $result = $this->connectorService->requestApiAction(
                    self::ACTION_UPDATE,
                    $this->formatData($item),
                    ['id' => $mapping->getExternalId() ]
                );
                $this->handleAfterApiRequest($item, $result, $mapping);
            } else {
                $mapping = $this->checkMappingInDb($item, $this::SMART_COLLECTION_ENTITY);
                if ($mapping) {
                    $this->stepExecution->addWarning("Skipped Smart Collection", ['category' => $item['code']], new \DataInvalidItem(['code' => $item['code']]));
                    continue;
                }

                $result = $this->connectorService->requestApiAction(
                    self::ACTION_ADD,
                    $this->formatData($item)
                );

                $this->handleAfterApiRequest($item, $result);
            }

            /* increment write count */
            $this->stepExecution->incrementSummaryInfo('write');
        }
    }

//     protected function formatData($item)
//     {
//         $labels = !empty($item['labels']) ? $item['labels'] : [];
//         $formatted = [
//             'title' => !empty($labels[$this->getDefaultLanguage()]) ?  $labels[$this->getDefaultLanguage()] : ($item['code']),
//             // 'handle' => $item['code'],
//         ];
//         return [ self::RESOURCE_WRAPPER => $formatted ];
//     }
}
