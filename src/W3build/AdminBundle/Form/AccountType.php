<?php
/**
 * Created by PhpStorm.
 * User: lukas_jahoda
 * Date: 15.1.15
 * Time: 16:39
 */

namespace W3build\AdminBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use W3build\AdminBundle\Form\Constraints\CurrentPassword;

class AccountType extends AbstractType {

    private $currentPasswordConstraint;

    function __construct(CurrentPassword $currentPasswordConstraint)
    {
        $this->currentPasswordConstraint = $currentPasswordConstraint;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', 'text', array(
            'label' => 'FirstName',
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'EnterFirstName'
            )
        ));

        $builder->add('surname', 'text', array(
            'label' => 'Surname',
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'EnterSurname'
            )
        ));

        $builder->add('email', 'email', array(
            'label' => 'Email',
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'EnterEmail'
            )
        ));

        $builder->add('currentPassword', 'password', array(
            'label' => 'CurrentPassword',
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'EnterCurrentPassword'
            ),
            'constraints' => array(
                $this->currentPasswordConstraint
            ),
            'mapped' => false,
            'required' => false,
        ));

        $builder->add('newPassword', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'The password fields must match.',
            'options' => array('attr' => array('class' => 'password-field')),
            'first_options'  => array(
                'label' => 'NewPassword',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'EnterNewPassword'
                ),
            ),
            'second_options' => array(
                'label' => 'ConfirmNewPassword',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'EnterNewsPasswordConfirm'
                ),
            ),
            'first_name'  => 'password',
            'second_name' => 'passwordConfirm',
            'mapped' => false,
            'required' => false,
        ));

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) use($builder){
                $form = $event->getForm();

                if($form->get('newPassword')->get('password')->getData()){
                    $builder->get('currentPassword')->setRequired(true);
                }
            }
        );

        $builder->add('buttons', new ButtonsType(), array('mapped' => false));
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'admin_profile';
    }


}