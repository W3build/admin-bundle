<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 11.9.14
 * Time: 23:06
 */

namespace W3build\Admin\DataGridBundle\DataGrid;

use W3build\Admin\DataGridBundle\DataGrid;

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
            new \Twig_SimpleFunction('data_grid', array($this, 'render'), array('pre_escape' => 'html', 'is_safe' => array('html'))),
        );
    }
    public function render(DataGrid $dataGrid){
        return $this->twig->render('W3buildAdminDataGridBundle::dataGrid.html.twig', array('dataGrid' => $dataGrid));
    }

    public function getName(){
        return 'twig_data_grid_extension';
    }

} 