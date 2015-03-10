<?php
/**
 * Created by PhpStorm.
 * User: lukas_jahoda
 * Date: 12.1.15
 * Time: 22:53
 */

namespace W3build\AdminBundle\Repository;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use W3build\AdminBundle\Entity\User;

class UserRepository {

    /**
     * @var EntityManager
     */
    private $entityManager;

    function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find($id){
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('user', 'roles')
            ->from(User::ENTITY_NAME, 'user')
            ->join('user.roles', 'roles')
            ->where('user.id = :id')
            ->andWhere('user.deleted = 0')
            ->setParameter('id', (int) $id);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $persister = $this->entityManager->getUnitOfWork()->getEntityPersister(User::ENTITY_NAME);

        return $persister->load($criteria, null, null, array(), 0, 1, $orderBy);
    }

    public function adminDataGridQuery(){
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('user')
            ->from(User::ENTITY_NAME, 'user');

        return $queryBuilder;
    }

    public function findAll(){
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('user')
            ->from(User::ENTITY_NAME, 'user');

        return $queryBuilder->getQuery()->getResult();
    }

    public function save(User $user){
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }


}