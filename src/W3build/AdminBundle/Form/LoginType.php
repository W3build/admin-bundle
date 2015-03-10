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

class LoginType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array(
                'label' => 'Email',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Email'
                )
            ))

            ->add('password', 'password', array(
                'label' => 'Password',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Password'
                )
            ))

            ->add('show_password', 'checkbox', array(
                'label' => 'Show',
                'required' => false
            ))

            ->add('remember_me', 'checkbox', array(
                'label' => 'RememberMe',
                'required' => false
            ))

            ->add('save', 'submit', array(
                'label' => 'Login',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ));
    }

    public function getName()
    {
        return 'admin_login';
    }

} 