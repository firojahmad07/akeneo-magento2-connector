<?php

namespace Spygar\Magento2\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Spygar\Magento2\Entity\Credentials;
/**
 * CredentialsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CredentialsRepository extends EntityRepository
{
    /** @var EntityManagerInterface */
    protected $entityManager;
    
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $classMeta = $entityManager->getClassMetadata(Credentials::class);

        parent::__construct($entityManager, $classMeta);
    }

    /**
    * Create a query builder used for the datagrid
    *
    * @return QueryBuilder
    */
    public function createDatagridQueryBuilder()
    {
        return $this->createQueryBuilder($this->getAlias());
    }

    /**
     * @return string
     */
    protected function getAlias()
    {
        return 'c';
    }

    /**
     * Get Credential with Details 
     * @return Array
     */
    public function getCredentialWithDetail($id)
    {
        $credential = $this->findOneById($id);
       
        $data = [];
        if ($credential) {
            $data = [
                'id'    => $credential->getId(),
                'url'   => $credential->getUrl(),
                'username' => $credential->getApiKey(),
                'password' => $credential->getPassword(),
                'resources'     => json_decode($credential->getResources(), true)
            ];            
        }
       
        return $data;
    }
}