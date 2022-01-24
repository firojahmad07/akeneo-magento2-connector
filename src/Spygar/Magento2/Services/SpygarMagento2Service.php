<?php
namespace Spygar\Magento2\Services;

use Doctrine\ORM\EntityManager;
use Spygar\Magento2\Entity\ApiConnection;
use Spygar\Magento2\Repository\CredentialsRepository;
use Spygar\Magento2\Repository\DataMappingRepository;
use Spygar\Magento2\Components\OAuthClient;

class SpygarMagento2Service 
{
    /** @var EntityManager */
    public $entityManager;

    /** @var CredentialsRepository */
    public $credentialRepository;

    /** @var DataMappingRepository */
    public $dataMappingRepository;

    /** @var */
    public $stepExecution;
    
    /**
     * @param $entityManager   
     * @param $credentialRepository
     */
    public function __construct(
        EntityManager $entityManager,
        CredentialsRepository $credentialRepository,
        DataMappingRepository $dataMappingRepository
        )
    {
        $this->entityManager         = $entityManager;
        $this->credentialRepository  = $credentialRepository;
        $this->dataMappingRepository = $dataMappingRepository;
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
    public function requestApiAction($credential, $endPoint, $payload, $storeViewCode)
    {
        $client = new OAuthClient($credential);
        
        return $client->request($endPoint, $payload, $storeViewCode);
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
    public function getCredentialById($id)
    {
        $credential = $this->credentialRepository->getCredentialWithDetail($id);
        $data       = [];
        if(!empty($credential)) {
            $data = [
                'id'  => $credential['id'],
                'url' => $credential['url'],
                'access_token'     => $credential['access_token'],
                'storeViewMapping' => $credential['storeViewMapping']
            ];
        }

        return $data;
    }

    /** Get active credentail data which is assign into job */
    public function getJobCredentialData()
    {
        $jobParameters = $this->stepExecution->getJobParameters();
        $credentialId  = $jobParameters->has('credential') ? $jobParameters->get('credential') : null;

        return $this->getCredentialById($credentialId);
    }

    /** Get Mapping  */
    public function getMappingData($params)
    {
        return $this->dataMappingRepository->findOneBy($params);
    }

    /** Create new or Update existing mapping */
    public function createOrUpdateMapping($params, $mapping)
    {
        $this->dataMappingRepository->createOrUpdateMapping($params, $mapping);
    }
}