<?php

namespace Spygar\Magento2\Connector\Export\Writer;

use Spygar\Magento2\Services\SpygarMagento2Service;
use Akeneo\Tool\Component\Batch\Step\StepExecutionAwareInterface;
use Akeneo\Tool\Component\Batch\Model\StepExecution;
use Akeneo\Tool\Component\Batch\Item\DataInvalidItem;

/**
 * Add resources to Magento2
 *
 * @author    Spygar
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
        if (!empty($this->connectorService) && $this->connectorService instanceof SpygarMagento2Service) {
            $this->connectorService->setStepExecution($stepExecution);
        }
    }

}
