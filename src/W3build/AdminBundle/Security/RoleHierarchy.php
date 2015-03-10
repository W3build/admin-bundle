<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 15.2.14
 * Time: 21:40
 */

namespace W3build\AdminBundle\Security;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Role;
use \Doctrine\Common\Cache\ApcCache;
use Symfony\Component\Security\Core\Role\RoleInterface;
use W3build\AdminBundle\Repository\RoleRepository;

class RoleHierarchy extends Role\RoleHierarchy {

    /**
     * @var ApcCache
     */
    private $apcCache;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * @var string
     */
    private $environment;

    /**
     * @var array
     */
    private $roles = array();

    public function __construct(RoleRepository $roleRepository, ApcCache $apcCache, $environment)
    {
        $this->roleRepository = $roleRepository;
        $this->apcCache = $apcCache;
        $this->environment = $environment;
        $hierarchy = $this->buildRolesTree();

        parent::__construct($hierarchy);
    }

    private function loadRoles($roles, &$hierarchy){

        /** @var \W3build\AdminBundle\Entity\Role $role */
        foreach($roles as $role){
            if (array_key_exists($role->getRole(), $this->roles)){
                throw new Exception('Role was already set');
            }
            $hierarchy[$role->getRole()] = array();

            if($role->getParent()){
                if (!array_key_exists($role->getParent()->getRole(), $hierarchy)){
                    throw new Exception('Parent role does not exists');
                }
                array_push($hierarchy[$role->getParent()->getRole()], $role->getRole());
            }

            if($role->getChildren()){
                $this->loadRoles($role->getChildren()->toArray(), $hierarchy);
            }
        }
    }


    public function buildRolesTree(){
        if($this->environment == 'prod'){
            if($this->apcCache->contains('role_hierarchy_database')){
                return unserialize($this->apcCache->fetch('role_hierarchy_database'));
            }
        }

        $roles = $this->roleRepository->getTopLevels();
        $hierarchy = array();
        $this->loadRoles($roles, $hierarchy);

        if($this->environment == 'prod'){
            $this->apcCache->save('role_hierarchy_database', serialize($hierarchy));
        }

        return $hierarchy;
    }

} 