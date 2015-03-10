<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 21.2.14
 * Time: 22:21
 */

namespace W3build\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use W3build\AdminBundle\Entity\Role;

class UserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', 'text', array(
                'label' => 'FirstName',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'EnterFirstName'
                )
        ));

        $builder->add('email', 'email', array(
                    'label' => 'Email',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'EnterEmail'
                    )
        ));

        $builder->add('surname', 'text', array(
                    'label' => 'Surname',
                    'attr' => array(
                        'class' => 'form-control',
                        'placeholder' => 'EnterSurname'
                    )
        ));

        $builder->add('password', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'The password fields must match.',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => true,
                    'first_options'  => array(
                        'label' => 'Password',
                        'attr' => array(
                            'class' => 'form-control',
                            'placeholder' => 'EnterPassword'
                        ),
                    ),
                    'second_options' => array(
                        'label' => 'Repeat Password',
                        'attr' => array(
                            'class' => 'form-control',
                            'placeholder' => 'EnterPasswordConfirm'
                        ),
                    ),
                    'first_name'  => 'password',
                    'second_name' => 'password_confirm',
        ));

        $builder->add('roles', 'entity', array(
            'class' => Role::ENTITY_NAME,
            'label' => 'Role',
            'property' => 'name',
            'attr' => array(
                'class' => 'form-control'
            ),
            'mapped' => 'role',
            'empty_value' => '',
            'multiple' => true,
            'expanded' => true
        ));


        $builder->add('buttons', new ButtonsType(), array('mapped' => false));
    }

    public function getName()
    {
        return 'admin_user';
    }

} 