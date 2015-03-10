<?php
/**
 * Created by PhpStorm.
 * User: lukas_jahoda
 * Date: 12.1.15
 * Time: 22:53
 */

namespace W3build\AdminBundle\Repository;

use Doctrine\ORM\EntityManager;
use W3build\AdminBundle\Entity\Role;

class RoleRepository {

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
        $queryBuilder->select('role')
            ->from(Role::ENTITY_NAME, 'role')
            ->where('role.id = :id')
            ->setParameter('id', $id);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function getTopLevels(){
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('role', 'children')
            ->from(Role::ENTITY_NAME, 'role')
            ->leftJoin('role.children', 'children')
            ->where('role.parent IS NULL');

        return $queryBuilder->getQuery()->getResult();
    }

    public function findAll(){
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('role')
            ->from(Role::ENTITY_NAME, 'role');

        return $queryBuilder->getQuery()->getResult();
    }

    public function save(Role $role){
        $this->entityManager->persist($role);
        $this->entityManager->flush();
    }

}