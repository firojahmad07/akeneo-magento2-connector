<?php

namespace Spygar\Magento2\Connector\Export\Writer;

use Spygar\Magento2\Services\SpygarMagento2Service;
use Akeneo\Tool\Component\Batch\Step\StepExecutionAwareInterface;
use Akeneo\Tool\Component\Batch\Model\StepExecution;
/**
 * Add resources to Magento2
 *
 * @author    Spygar
 * @copyright 2010-2017 Spygar pvt. ltd.
 * @license   https://store.Spygar.com/license.html
 */

class BaseWriter implements StepExecutionAwareInterface
{
    protected $connectorService;

    
    public function __construct(SpygarMagento2Service $connectorService)
    {
        $this->connectorService = $connectorService;
    }
    /**
     * {@inheritdoc}
     */
    public function setStepExecution(StepExecution $stepExecution)
    {
        $this->stepExecution = $stepExecution;
        if (!empty($this->connectorService) && $this->connectorService instanceof SpygarShopifyService) {
            $this->connectorService->setStepExecution($stepExecution);
        }
    }

    public function checkMapping($code)
    {

    }
}
