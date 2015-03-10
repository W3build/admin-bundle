<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 11.9.14
 * Time: 21:06
 */

namespace W3build\Admin\DataGridBundle\DataGrid\Column;

use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use W3build\Admin\DataGridBundle\DataGrid;

abstract class ColumnAbstract {

    private $identifier;

    private $label;

    private $data;

    private $row;

    private $customDataParser;

    private $dataGrid;

    public function __construct($identifier, $label = null, $data = null){
        $this->identifier = $identifier;
        $this->label = $label;
        $this->data = $data;
    }

    public function setCustomDataParser($function){
        if(!is_callable($function)){
            throw new UnexpectedTypeException($function, 'function');
        }

        $this->customDataParser = $function;
    }

    public function setLabel($label){
        $this->label = $label;
    }

    public function getLabel(){
        if(!$this->label){
            $this->label = ucfirst($this->identifier);
        }

        return $this->label;
    }

    public function setData($data){
        $this->data = $data;

        return $this;
    }

    public function getData(){
        if($this->customDataParser){
            return $this->customDataParser($this->dataGrid->getResult()->current());
        }

        if(!$this->data){
            $this->data = '$row->' . $this->identifier;
        }

        return $this->dataGrid->getRowParser()->parse($this->data);
    }

    public function setDataGrid(DataGrid $dataGrid){
        $this->dataGrid = $dataGrid;

        return $this;
    }

} 