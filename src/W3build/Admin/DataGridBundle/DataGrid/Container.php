<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 11.9.14
 * Time: 20:39
 */

namespace W3build\Admin\DataGridBundle\DataGrid;

use W3build\Admin\DataGridBundle\DataGrid;

class Container {

    private $instances = array();

    /**
     * @param $identifier
     * @return DataGrid
     * @throws \InvalidArgumentException
     */
    public function load($identifier){
        if(!in_array($identifier, $this->instances)){
            throw new \InvalidArgumentException("Data grid with identifier " . $identifier . " doesn't exists");
        }

        return $this->instances[$identifier];
    }

    /**
     * @param $identifier
     * @param DataGrid $dataGridInstance
     * @throws \InvalidArgumentException
     */
    public function register($identifier, DataGrid $dataGridInstance){
        if(in_array($identifier, $this->instances)){
            throw new \InvalidArgumentException("Data grid with identifier " . $identifier . " already exists");
        }

        $this->instances[] = $dataGridInstance;
    }

} 