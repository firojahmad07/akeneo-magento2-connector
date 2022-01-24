<?php
namespace Spygar\Magento2\Controller\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\NotFoundHttpException;
use Spygar\Magento2\Entity\Credentials;
use Symfony\Component\HttpFoundation\JsonResponse;
use Spygar\Magento2\Components\OAuthClientHelper;
use Doctrine\ORM\EntityManager;
use Spygar\Magento2\Repository\CredentialsRepository;

class CredentialController extends AbstractController
{
    /** @var EntityManager */

    private $entityManager;

    /** @var CredentialsRepository */

    private $credentialRepository;
    
    /** @var OAuthClientHelper*/
    protected $oauthClientHelper;

    public function __construct(
        CredentialsRepository $credentialRepository,
        EntityManager $entityManager, 
        OAuthClientHelper $oauthClientHelper )
    {
        $this->credentialRepository = $credentialRepository;
        $this->entityManager        = $entityManager;
        $this->oauthClientHelper    = $oauthClientHelper;
    }

    public function create(Request $request)
    {
        $params    = json_decode($request->getContent(), true);
        $data      = [];
        // $resources = [];
        if (isset($params['id'])) {
            $credential = $this->credentialRepository->findOneById($params['id']); 
            // $resources  = !empty($credential) ? $credential->getResources() : [];
            $credential = !empty($credential) ? $credential : new Credentials;            
        } else {
            $credential = new Credentials;   
        }
       
       
        $validateCredential = $this->oauthClientHelper->validateCredential($params);
      
        if (200 == $validateCredential['status'])
        {
            $formattedStoreView['stores'] = $this->getFormattedStoreViewData($validateCredential['data']);
            $credential->setUrl($params["url"]);
            $credential->setAccessToken($params["access_token"]);
            $credential->setExtras(json_encode([$params]));
            $credential->setResources($formattedStoreView);
            $credential->setActive(true);
            $this->entityManager->persist($credential);
            $this->entityManager->flush();
    
            $data['meta'] = [ "id" => $credential->getId()];
            
        } 
       
        return new JsonResponse($data);
    }

    /** update credential */
    public function update(Request $request)
    {
        $params     = json_decode($request->getContent(), true);
        $credential = $this->credentialRepository->findOneById($params['id']); 
        $attributeMappings   = [];
        $resources['stores'] = $params['stores'];
        $resources['storeViewMapping'] = $params['storeViewMapping'];
        // dump($);die;
        if(!empty($params['parentAttributes'])) {
            $attributeMappings['parentAttributes'] = $params['parentAttributes'];
        }
        if(!empty($params['variantAttributes'])) {
            $attributeMappings['variantAttributes'] = $params['variantAttributes'];
        }

        $credential->setResources($resources);
        if(!empty($attributeMappings)) {
            // dump($attributeMappings);die;
            $credential->setExtras(json_encode($attributeMappings));
        }
        $this->entityManager->persist($credential);
        $this->entityManager->flush();
        
        return new JsonResponse(['id' => $credential->getId()]);
    }

    /** get formatted store view */
    public function getFormattedStoreViewData($storeViewData)
    {
        $data = [];
        $allStoreView = [];
        foreach($storeViewData as $value) {
            if($value['code'] == 'admin') {
                $allStoreView[] = $value;
            } else {
                $data[] = $value;
            }
        }

        return array_merge($allStoreView, $data);
    }
    /**
     * Change status of credential
     *     
     * @param string  $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function toggle(Credentials $id)
    {
        $credential = $id;
        if ($credential) {
            $credential->setActive($credential->getActive() ? 0 : 1);
            $this->entityManager->persist($credential);
            $this->entityManager->flush();
        }
        
        return new Response(null, Response::HTTP_NO_CONTENT);
        // return new JsonResponse(['route' => 'spygar_shopify_credentials']);
    }

    /**
     * Delete credentials
     *
     * @param string $id
     *
     * @return JsonResponse
     */

    public function delete(Credentials $id)
    {
        $credential = $id;
        if (!$credential) {
            // throw new NotFoundHttpException(
            //     sprintf('Instance with id "%s" not found', $id)
            // );
        }

        $this->entityManager->remove($credential);
        $this->entityManager->flush();
 
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Get Credetials Details by ID
     * @param int $id
     * @return jsonResponse
     */

    public function details($id)
    { 
        $credential = $this->credentialRepository->getCredentialWithDetail($id);

        return new JsonResponse($credential);
    }

    public function getList()
    {
        $credentialQB = $this->credentialRepository->createQueryBuilder('c')
                            ->select('c.id, c.url');

        return new JsonResponse($credentialQB->getQuery()->getResult());
    }
}