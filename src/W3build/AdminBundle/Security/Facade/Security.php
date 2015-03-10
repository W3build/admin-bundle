<?php
/**
 * Created by PhpStorm.
 * User: lukas_jahoda
 * Date: 13.1.15
 * Time: 0:42
 */

namespace W3build\AdminBundle\Security\Facade;

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\User\UserInterface;

class Security {

    private $aclProvider;

    /**
     * @var SecurityContext
     */
    private $securityContext;

    /**
     * @var EncoderFactory
     */
    private $encoderFactory;

    function __construct($aclProvider, SecurityContext $securityContext, EncoderFactory $encoderFactory)
    {
        $this->aclProvider = $aclProvider;
        $this->securityContext = $securityContext;
        $this->encoderFactory = $encoderFactory;
    }

    public function setObjectOwner($object){
        $this->grantUserAccess($object, MaskBuilder::MASK_OWNER);
    }

    public function encodePassword(UserInterface $user, $password = null){
        if(!$password){
            $password = $user->getPassword();
        }

        $encoder = $this->encoderFactory->getEncoder($user);
        return $encoder->encodePassword($password, $user->getSalt());
    }

    public function grantUserAccess($object, $mask){
        // creating the ACL
        $objectIdentity = ObjectIdentity::fromDomainObject($object);
        $acl = $this->aclProvider->createAcl($objectIdentity);

        // retrieving the security identity of the currently logged-in user
        $user = $this->securityContext->getToken()->getUser();
        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        // grant owner access
        $acl->insertObjectAce($securityIdentity, $mask);
        $this->aclProvider->updateAcl($acl);
    }

    /**
     * @return UserInterface
     */
    public function getLoggedUser(){
        return $this->securityContext->getToken()->getUser();
    }

}