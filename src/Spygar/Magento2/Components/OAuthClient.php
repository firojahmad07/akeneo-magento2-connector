<?php
namespace Spygar\Magento2\Components;

use Spygar\Magento2\Traits\EndPointsTrait;

class OAuthClient
{
    use EndPointsTrait;
    
    /** @var [] */
    private $activeCredential;

    /** @var string */
    private $_url;

    /** @var object */
    private $_curl;

    private $_method;

    private $apiKey;
    private $apiPassword;

    public function __construct(array $activeCredential)
    {
        $this->activeCredential = $activeCredential;
        $this->_url             = isset($this->activeCredential['url']) ? $this->activeCredential['url'] : "";
        $this->accessToken      = isset($this->activeCredential['access_token']) ? $this->activeCredential['access_token'] : "";
      
    }

    public function request($requestUrl, $payload, $storeViewCode)
    {
        $this->_curl  = \curl_init();
        return $this->createCurl($requestUrl, $payload, $storeViewCode);
    }

    /** get Generated Url */

    public function generateApiUrl($url, $storeViewCode, $requestParams = [])
    {

        $endPointData  = isset($this->endPoints[$url]) ? $this->endPoints[$url] : "";
        $this->_method = $endPointData['method'];
        $fullUrl = ($storeViewCode == 'admin') ? \str_ireplace("{_store}/", "", $endPointData['url']) : \str_ireplace("{_store}", $storeViewCode, $endPointData['url']);
      
        return trim($this->_url, "/").$fullUrl;
    }
    
 
    public function createCurl($requestUrl, $payload = [], $storeViewCode)
    {
        $this->_url = $this->generateApiUrl($requestUrl, $storeViewCode);  
       
        \curl_setopt($this->_curl, CURLOPT_URL, $this->_url);        
        \curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($this->_curl, CURLOPT_HTTPHEADER, $this->getRequestHeaders());
     

        if(in_array($this->_method, ['POST']))
        {
            \curl_setopt($this->_curl, CURLOPT_POST, true);
            \curl_setopt($this->_curl, CURLOPT_POSTFIELDS, json_encode($payload));
        }
       
        $response   = \curl_exec($this->_curl);
        $status     = \curl_getinfo($this->_curl, CURLINFO_HTTP_CODE);

        curl_close($this->_curl);
        $responseData['data']   = json_decode($response, true);
        $responseData['status'] = $status;

        return $responseData;

    }
 
    public function getRequestHeaders()
    {
        return [
            'Accept: application/json',
            'Content-type: application/json',
            'Cache-Control: no-cache',
            'Cache-Control: max-age=0',
            'Authorization: Bearer ' .$this->accessToken,
        ];
    }

}