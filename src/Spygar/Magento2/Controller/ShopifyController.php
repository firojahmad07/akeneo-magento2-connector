<?php
namespace Spygar\Magento2\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spygar\Magento2\Entity\ApiConnection;
use Spygar\Magento2\Services\SpygarMagento2Service;
use Symfony\Component\HttpFoundation\Request;

class ShopifyController extends AbstractController
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
            'name' => 'title',
            'label' => 'spygar_shopify.attribute.title',
            'placeholder' => '',
            'types' => [
                'pim_catalog_text',
            ],
            'tooltip' => 'supported attributes types: text',
        ],
        [
            'name' => 'body_html',
            'label' => 'spygar_shopify.attribute.description',
            'placeholder' => '',
            'types' => [
                'pim_catalog_textarea',
            ],
            'tooltip' => 'supported attributes types: textarea',
        ],        
        [
            'name' => 'price',
            'label' => 'spygar_shopify.attribute.price',
            'placeholder' => '',
            'types' => [
                'pim_catalog_price_collection',
            ],
            'tooltip' => 'supported attributes types: price',
        ],
        [
            'name' => 'compare_at_price',
            'label' => 'spygar_shopify.attribute.compare_at_price',
            'placeholder' => '',
            'types' => [
                'pim_catalog_price_collection',
            ],
            'tooltip' => 'supported attributes types: price',
        ],
        [
            'name' => 'quantity',
            'label' => 'spygar_shopify.attribute.quantity',
            'placeholder' => '',
            'types' => [
                'pim_catalog_number',
            ],
            'tooltip' => 'supported attributes types: number',
        ],
        [
            'name' => 'vendor',
            'label' => 'spygar_shopify.attribute.vendor',
            'placeholder' => '',
            'types' => [
                'pim_catalog_simpleselect',
             ],
            'tooltip' => 'supported attributes types: simple select',
        ],
        [
            'name' => 'product_type',
            'label' => 'spygar_shopify.attribute.product_type',
            'placeholder' => '',
            'types' => [
                'pim_catalog_simpleselect',
            ],
            'tooltip' => 'supported attributes types: simple select',
        ],
        [
            'name' => 'fulfillment_service',
            'label' => 'spygar_shopify.attribute.fulfillment_service',
            'placeholder' => '',
            'types' => [
                'pim_catalog_simpleselect',
            ],
            'tooltip' => 'supported attributes types: simple select',
        ],

        [
            'name' => 'inventory_management',
            'label' => 'spygar_shopify.attribute.inventory_management',
            'placeholder' => '',
            'types' => [
                'pim_catalog_simpleselect',
            ],
            'tooltip' => 'supported attributes types: simple select',
        ],
        [
            'name' => 'tags',
            'label' => 'spygar_shopify.attribute.tags',
            'placeholder' => '',
            'types' => [
                'pim_catalog_multiselect',
            ],
            'tooltip' => 'supported attributes types: multi select',
        ],
        [
            'name' => 'weight',
            'label' => 'spygar_shopify.attribute.weight',
            'placeholder' => '',
            'types' => [
                'pim_catalog_metric',
            ],
            'tooltip' => 'supported attributes types: metric',
        ],  
    ];
}