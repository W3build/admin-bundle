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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use W3build\AdminBundle\Entity\User;

class LoadSuperAdmin extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    private $data = array(
        'lukas.jahoda@w3build.cz' => array(
            'firstName' => 'Lukáš',
            'surname' => 'Jahoda',
            'password' => '6456552',
            'active' => true,
        ),
    );

    public function load(ObjectManager $manager){

        foreach($this->data as $email => $data){
            $entity = new User();
            $entity->setFirstName($data['firstName'])
                ->setSurname($data['surname'])
                ->setPassword($data['password'])
                ->setActive($data['active'])
                ->setEmail($email)
            ;

            $entity->addRole($this->getReference('admin_role_SuperAdmin'));

            $encoder = $this->container
                ->get('security.encoder_factory')
                ->getEncoder($entity);
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            $entity->setPassword($password);

            $manager->persist($entity);
            $manager->flush();
        }
    }

    /**
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;

        return $this;
    }


    public function getOrder(){
        return 2;
    }

}