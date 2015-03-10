<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 14.9.14
 * Time: 16:47
 */

namespace W3build\AdminBundle\Twig;

use Symfony\Component\HttpFoundation\Session\Session;

class FlashBagExtension extends \Twig_Extension {

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var \Twig_Environment
     */
    private $session;

    public function __construct(\Twig_Environment $twig, Session $session){
        $this->twig = $twig;
        $this->session = $session;
    }

    public function getFunctions(){
        return array(
            new \Twig_SimpleFunction('flash_messages', array($this, 'render'), array('pre_escape' => 'html', 'is_safe' => array('html'))),
        );
    }
    public function render(){
        $messages = '';
        if($errorMessages = $this->session->getFlashBag()->get('error')){
            foreach($errorMessages as $message){
                $messages .= $this->twig->render('W3buildAdminBundle:FlashMessages:error.html.twig', array('message' => $message));
            }
        }

        if($warningMessages = $this->session->getFlashBag()->get('warning')){
            foreach($warningMessages as $message){
                $messages .= $this->twig->render('W3buildAdminBundle:FlashMessages:warning.html.twig', array('message' => $message));
            }
        }

        if($successMessages = $this->session->getFlashBag()->get('success')){
            foreach($successMessages as $message){
                $messages .= $this->twig->render('W3buildAdminBundle:FlashMessages:success.html.twig', array('message' => $message));
            }
        }

        return $messages;
    }

    public function getName(){
        return 'admin_flash_bag';
    }

} 