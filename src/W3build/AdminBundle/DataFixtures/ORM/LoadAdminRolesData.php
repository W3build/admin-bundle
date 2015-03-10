<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 1.8.14
 * Time: 9:27
 */

namespace W3build\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use W3build\AdminBundle\Entity\Role;

class LoadAdminRolesData extends AbstractFixture implements OrderedFixtureInterface {

    private $data = array(
        'SuperAdmin' => array(),
        'Admin' => array('SuperAdmin'),
        'LocalAdmin' => array('Admin'),
        'Editor' => array('LocalAdmin'),
    );

    public function load(ObjectManager $manager){

        foreach($this->data as $id => $data){
            $entity = new Role();
            $entity->setName($id);

            if($data){
                $entity->setParent($this->getReference('admin_role_' . $data[0]));
            }

            $manager->persist($entity);
            $manager->flush();

            $this->addReference('admin_role_' . $id, $entity);
        }
    }

    public function getOrder(){
        return 1;
    }

}