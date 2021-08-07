<?php
namespace Spygar\Magento2\Traits;

/**
 * 
 */
trait EndPointsTrait
{
    /** Get Sylius API End Points */
    private $endPoints = 
    [
        "validate_credentials"=> "{{url}}/rest/V1/store/storeViews",
    ];
}
