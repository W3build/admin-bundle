<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 21.2.14
 * Time: 22:21
 */

namespace W3build\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ButtonsType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('save', 'submit', array(
            'label' => 'Save',
            'attr' => array(
                'class' => 'btn btn-primary fa fa-save'
            )
        ));

        $builder->add('save2', 'submit', array(
            'label' => 'Save',
            'attr' => array(
                'class' => 'btn btn-primary fa fa-save'
            )
        ));

    }

    public function getName()
    {
        return 'admin_buttons';
    }

}