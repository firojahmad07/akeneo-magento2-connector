<?php
namespace Spygar\Magento2\Components;

class OAuthClient
{
    private $activeCredential;
    private $_url;
    private $_curl;
    private $_method;

    private $apiKey;
    private $apiPassword;

    public function __construct(array $activeCredential)
    {
        $this->activeCredential = $activeCredential;
        $this->_url             = isset($this->activeCredential['url']) ? $this->activeCredential['url'] : "";
        $this->apiKey           = isset($this->activeCredential['username']) ? $this->activeCredential['username'] : "";
        $this->apiPassword      = isset($this->activeCredential['password']) ? $this->activeCredential['password'] : "";
       
    }

    public function request($requestUrl, $payload, $method, $header = [])
    {
        $this->_method = $method;
        $this->_curl  = \curl_init();

        return $this->createCurl($requestUrl, $payload, $method, $header);
    }

    /** get Generated Url */

    public function generateApiUrl($url, $data)
    {

        $fullUrl = isset($this->endPoints[$url]) ? $this->endPoints[$url] : "";
        $fullUrl = \str_ireplace("{{url}}", $this->activeCredential['url'], $fullUrl);

        switch($url)
        {
            case 'get_access_token':
                $params = [
                    "client_id=". $this->activeCredential['client_id'],
                    "client_secret=" . $this->activeCredential['client_secret'],
                    "grant_type=" . "password",
                    "username=" . $this->activeCredential['username'],
                    "password=" . $this->activeCredential['password'],
                ];
                $fullUrl = $fullUrl . \implode("&", $params);
            break;
            case 'get_active_locals':
                $params = [
                    "page=1",
                    "limit=25"
                ];
                $fullUrl = $fullUrl . \implode("&", $params);
            break;
            default:
            
            break;
        }

        

        return $fullUrl;

    }
    /** Get Sylius API End Points */
    private $endPoints = 
    [
        "get_access_token"    => "{{url}}api/oauth/v2/token?",
        "get_active_locals"   => "{{url}}api/v1/locales/?",
        "validate_credentials"=> "{{url}}/admin/oauth/access_scopes.json"
    ];

    // https://k97d.myshopify.com/
 
    public function createCurl($requestUrl, $payload = [], $method = "GET", $header = [])
    {
        $this->_url = $this->generateApiUrl($requestUrl, []);        
        \curl_setopt($this->_curl, CURLOPT_URL, $this->_url);
      
    //   \curl_setopt($this->_curl, CURLOPT_FOLLOWLOCATION, $this->_followlocation);
    //   \curl_setopt($this->_curl, CURLOPT_COOKIEJAR,$this->_cookieFileLocation);
    //   \curl_setopt($this->_curl, CURLOPT_COOKIEFILE,$this->_cookieFileLocation);
     

        \curl_setopt($this->_curl, CURLOPT_HTTPHEADER, $this->getRequestHeaders());
        if(in_array($method, ['POST']))
        {
            \curl_setopt($this->_curl, CURLOPT_POST, true);
            \curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $payload);
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
            'Authorization: Basic ' . base64_encode($this->apiKey.":".$this->apiPassword),
        ];
    }

}