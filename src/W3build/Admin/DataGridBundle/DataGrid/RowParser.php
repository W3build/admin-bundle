<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 14.9.14
 * Time: 1:35
 */

namespace W3build\Admin\DataGridBundle\DataGrid;

use Symfony\Component\PropertyAccess\PropertyAccess;

class RowParser {

    private $row;

    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    private $propertyAccessor;

    private function parseObjectDataFromRow($param){
        $param = preg_replace('#^\$row->#', '', $param);
        $calls = explode('->', $param);
        $data = $this->getRow();
        foreach($calls as $call){
            if(preg_match('#^get.*\(\)#', $call)){
                $call = str_replace(array('(', ')'), '', $call);
                $data = call_user_func(array($data, $call));
            }
            else {
                $data = $this->propertyAccessor->getValue($data, $call);
            }
        }

        if(is_bool($data)){
            return $data ? 'Yes' : 'No';
        }

        return $data;
    }

    private function parseArrayDataFromRow($param){
        if(preg_match_all('#\[\'?"?([a-zA-Z0-9_\-]*)\'?"?\]#', $param, $calls)){
            $data = $this->getRow();
            foreach($calls as $call){
                $data = $this->propertyAccessor($data, $call);
            }


            if(is_bool($data)){
                return $data ? 'Yes' : 'No';
            }
            return $data;
        }

        return null;
    }

    private function getRow(){
        return $this->row;
    }

    public function __construct(){
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function setRow($row){
        $this->row = $row;

        return $this;
    }

    public function parse($param){
        if(!preg_match('#^\$row#', $param)){
            return $param;
        }

        if(is_object($this->getRow())){
            return $this->parseObjectDataFromRow($param);
        }
        else if(is_array($this->getRow())){
            return $this->parseArrayDataFromRow($param);
        }

        throw new UnexpectedTypeException($this->getRow(), 'object or array');
    }

} 