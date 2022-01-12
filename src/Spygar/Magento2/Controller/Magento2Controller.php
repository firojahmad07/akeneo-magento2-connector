<?php
namespace Spygar\Magento2\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spygar\Magento2\Entity\ApiConnection;
use Spygar\Magento2\Services\SpygarMagento2Service;
use Symfony\Component\HttpFoundation\Request;

class Magento2Controller extends AbstractController
{
    /** @var SpygarMagento2Service */
    public $spygarSyliusService;

    public function __construct(SpygarMagento2Service $spygarSyliusService)
    {
        $this->spygarSyliusService = $spygarSyliusService;
    }
    /**
     * GET API Connection
     * @param null
     * @return JsonResponse
     */

     public function getApiConnection()
     {
        $apiCredential = $this->spygarSyliusService->getApiConnection();        
        return new JsonResponse($apiCredential);
     }
     
     public function saveApiConnection(Request $request)
     {
        $params = json_decode($request->getContent(), true);
        $apiConnectionEntity = new ApiConnection;
        if(isset($params['id'])) {
            $apiConnectionEntity = $this->spygarSyliusService->getApiConnectionById($params['id']);
        } 

        $apiConnectionEntity->setClientId($params['clientId']);
        $apiConnectionEntity->setSecret($params['secret']);
        $apiConnectionEntity->setUsername($params['username']);
        $apiConnectionEntity->setPassword($params['password']);

        $this->spygarSyliusService->persistEntity($apiConnectionEntity);

        return $this->getApiConnection();
     }
    /**
     * GET Attribute mapping
     * @param null
     * @return JsonResponse
     */
    public function getMapping()
    {
        return new JsonResponse([]);
    }

     /**
      * GET Sylius attribute fields
      * @param null
      * @return JsonResponse
      */
      
      public function getFields()
      {
        return new JsonResponse($this->fieldsData);
      }

         
    private $fieldsData = [
        [
            'name' => 'sku',
            'label' => 'spygar_magento2.attribute.sku',
            'types' => [
                'pim_catalog_identifier',
            ],
            'tooltip' => 'supported attributes types: Identifier',
        ],[
            'name' => 'name',
            'label' => 'spygar_magento2.attribute.name',
            'types' => [
                'pim_catalog_text'
            ],
            'tooltip' => 'supported attributes types: text',
        ], [
            'name' => 'description',
            'label' => 'spygar_magento2.attribute.description',
            'types' => [
                'pim_catalog_textarea',
            ],
            'tooltip' => 'supported attributes types: textarea',
        ], [
            'name' => 'short_description',
            'label' => 'spygar_magento2.attribute.short_description',
            'types' => [
                'pim_catalog_textarea',
            ],
            'tooltip' => 'supported attributes types: textarea',
        ], [
            'name' => 'price',
            'label' => 'spygar_magento2.attribute.price',
            'placeholder' => '',
            'types' => [
                'pim_catalog_price_collection',
            ],
            'tooltip' => 'supported attributes types: price',
        ], [
            'name' => 'cost',
            'label' => 'spygar_magento2.attribute.cost',
            'placeholder' => '',
            'types' => [
                'pim_catalog_price_collection',
            ],
            'tooltip' => 'supported attributes types: price',
        ], [
            'name' => 'quantity_and_stock_status',
            'label' => 'spygar_magento2.attribute.quantity',
            'placeholder' => '',
            'types' => [
                'pim_catalog_number',
            ],
            'tooltip' => 'supported attributes types: number',
        ], [
            'name' => 'weight',
            'label' => 'spygar_magento2.attribute.weight',
            'placeholder' => '',
            'types' => [
                'pim_catalog_metric',
            ],
            'tooltip' => 'supported attributes types: metric',
        ],  
    ];
}