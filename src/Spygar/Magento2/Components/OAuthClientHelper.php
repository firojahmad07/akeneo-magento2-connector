<?php
namespace Spygar\Magento2\Components;

use Spygar\Magento2\Services\SpygarMagento2Service;

class OAuthClientHelper
{
    

    public function setJobExecution($jobExecution)
    {
        $this->jobExecution = $jobExecution;
    }
    /**
     * Request Api
     * @return []
     */
    public function request($requestUrl, $payload, $method, $urlParameters = [])
    {
        // $client = new OAuthClient();
        // return $client->request($requestUrl, $payload, $method);
    }
    /**
     * Validate Sylius API Credential
     * @param array
     * @return array
     */
    public function validateCredential($credential)
    {
        $client = new OAuthClient($credential);
        return $client->request("storeViews", [], "GET");
    }

   

}