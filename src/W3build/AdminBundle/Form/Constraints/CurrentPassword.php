<?php
namespace W3build\AdminBundle\Form\Constraints;

use Symfony\Component\Validator\Constraint;
use W3build\AdminBundle\Security\Facade\Security;

class CurrentPassword extends Constraint {

    const DEFAULT_PROPERTY = 'password';

    /**
     * @var Security
     */
    private $security;

    /**
     * @var string
     */
    private $message = 'CurrentPasswordInvalid';

    /**
     * @var string
     */
    private $property = self::DEFAULT_PROPERTY;

    /**
     * @param Security $security
     */
    public function __construct(Security $security){
        $this->security = $security;
        parent::__construct(array());
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param string $property
     * @return $this
     */
    public function setProperty($property)
    {
        $this->property = $property;
        return $this;
    }

    /**
     * @return Security
     */
    public function getSecurity()
    {
        return $this->security;
    }

}