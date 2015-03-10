<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 14.9.14
 * Time: 1:16
 */

namespace W3build\Admin\DataGridBundle\DataGrid\Action;

use W3build\Admin\DataGridBundle\DataGrid\Action\ActionAbstract;

class TwigExtension extends \Twig_Extension {

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig){
        $this->twig = $twig;
    }

    public function getFunctions(){
        return array(
            new \Twig_SimpleFunction('data_grid_action', array($this, 'render'), array('pre_escape' => 'html', 'is_safe' => array('html'))),
        );
    }
    public function render(ActionAbstract $action){
        return $this->twig->render('W3buildAdminDataGridBundle:Actions:' . $action->getTemplate() . '.html.twig', array('action' => $action));
    }

    public function getName(){
        return 'twig_data_grid_action_extension';
    }

} 