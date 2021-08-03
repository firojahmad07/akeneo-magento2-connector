<?php
namespace Spygar\Magento2\Services;

use Doctrine\ORM\EntityManager;
use Spygar\Magento2\Entity\ApiConnection;
use Spygar\Magento2\Repository\CredentialsRepository;

class SpygarMagento2Service 
{
    /** @var EntityManager */
    public $entityManager;

    /** @var CredentialsRepository */
    public $credentialRepository;

    /** @var */
    public $stepExecution;
    
    /**
     * @param $entityManager   
     * @param $credentialRepository
     */
    public function __construct(
        EntityManager $entityManager,
        CredentialsRepository $credentialRepository)
    {
        $this->entityManager        = $entityManager;
        $this->credentialRepository = $credentialRepository;
    }
    /** Set step execution instance */
    public function setStepExecution($stepExecution)
    {
        $this->stepExecution = $stepExecution;
    }
    /**
     * Get api Connection Repository By Id
     */
    public function getApiConnectionById($id)
    {
        return null;
        // return $this->entityManager->getRepository(ApiConnection::class)->findOneById($id);
    }

    /**
     * Get Api Connection Details
     * @return Array
     */
    public function getApiConnection($id = null)
    {
        // $apiConnectionRepo = $this->entityManager->getRepository(ApiConnection::class);
        // $apiConnectionQB = $apiConnectionRepo->createQueryBuilder('ap')
        //                     ->select('ap.id, ap.clientId, ap.secret, ap.username, ap.password');
        
        // if($id) {
        //     $apiConnectionQB->andWhere('ap.id =:id');
        //     $apiConnectionQB->setParameter('id', $id);
        // }

        return null;

        // return  !empty($apiConnectionQB->getQuery()->getResult()) ?  $apiConnectionQB->getQuery()->getSingleResult() : [];
    }

    /**
     * Persist entity with Value
     */
    public function persistEntity($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * Get Credential By Id
     * @return array
     */
    public function getCredential($id)
    {
        $credential = $this->credentialRepository->getCredentialWithDetail($id);
        dump($credential);die;
    }

}