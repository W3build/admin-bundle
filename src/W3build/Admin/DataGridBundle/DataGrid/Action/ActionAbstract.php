<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 11.9.14
 * Time: 23:19
 */

namespace W3build\Admin\DataGridBundle\DataGrid\Action;

use W3build\Admin\DataGridBundle\DataGrid;

abstract class ActionAbstract {

    protected $template;

    private $route;

    private $params = array();

    private $dataGrid;

    public function __construct($route, $params = array()){
        $this->route = $route;

        if(!$params){
            $params['id'] = '$row->getId()';
        }
        $this->params = $params;
    }

    public function getTemplate(){
        return $this->template;
    }

    public function getRoute(){
        return $this->route;
    }

    public function getParams(){
        $params = array();
        foreach($this->params as $key => $param){
            $params[$key] = $this->dataGrid->getRowParser()->parse($param);
        }

        return $params;
    }

    public function setDataGrid(DataGrid $dataGrid){
        $this->dataGrid = $dataGrid;

        return $this;
    }

} 