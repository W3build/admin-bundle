<?php
namespace W3build\AdminBundle\Form\Constraints;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CurrentPasswordValidator extends ConstraintValidator {

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint)
    {
        if(!$data = $this->context->getRoot()->getData()){
            throw new \Exception('Data must set for use CurrentPasswordValidator');
        }

        if(!$data instanceof UserInterface){
            throw new \Exception('Data must be instance of UserInterface for use CurrentPasswordValidator');
        }

        $password = $constraint->getSecurity()->encodePassword($data, $value);
        if($password != $data->getPassword()){
            $this->context->addViolation(
                $constraint->getMessage()
            );
        }
    }


}