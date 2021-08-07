<?php
namespace Spygar\Magento2\Components;

use Spygar\Magento2\Services\SpygarMagento2Service;

class OAuthClientHelper
{
    /** @var SpygarShopifyService */

    protected $spygarShopifyService;

    public function __construct(SpygarMagento2Service $spygarShopifyService)
    {
        $this->spygarShopifyService = $spygarShopifyService;
    }

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
        return $client->request("validate_credentials", [], "GET");
    }
    /** Get Active Sylius Locales
     * @param Array
     * @return Array 
     */
    public function getSyliusActiveLocals(array $credential, array $oauthToken)
    {
        $credential = array_merge($credential, $oauthToken);       
        $client = new OAuthClient($credential);
        $syliusActiveLocals = $client->request("get_active_locals", [], "GET");   
        $syliusActiveLocals['data'] = $this->formatSyliusLocales($syliusActiveLocals);
        
        return $syliusActiveLocals;
    }

    /**
     * Formate Sylius Local Items
     * @return Array
     */
    public function formatSyliusLocales(array $items)
    {
        $data = [];
        if ($items['status'] == "200") {
            foreach($items['data']['_embedded']['items'] as $item) {
               $data[] = [
                   "id" => $item['id'],
                   "code" => $item['code']
               ];
            }
        }

        return $data;
    }

}