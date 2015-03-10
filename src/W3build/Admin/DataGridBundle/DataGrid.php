<?php
/*
 * This file is part of the W3build DataGrid package.
 *
 * (c) Lukáš Jahoda <lukas.jahoda@w3build.cz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace W3build\Admin\DataGridBundle;

use W3build\Admin\DataGridBundle\DataGrid\Action\ActionAbstract;
use W3build\Admin\DataGridBundle\DataGrid\Column;
use W3build\Admin\DataGridBundle\DataGrid\Column\ColumnAbstract;
use W3build\Admin\DataGridBundle\DataGrid\Container;
use W3build\Admin\DataGridBundle\DataGrid\Exception\NotImplemendInterfaceException;
use W3build\Admin\DataGridBundle\DataGrid\Exception\UnexpectedTypeException;
use W3build\Admin\DataGridBundle\DataGrid\RowParser;
use W3build\Admin\DataGridBundle\DataGrid\TwigExtension;
use W3build\PaginateBundle\Paginate;
use W3build\PaginateBundle\Result;

/**
 * Generates the DataGrid
 *
 * @author Lukáš Jahoda <lukas.jahoda@w3build.cz>
 */
class DataGrid {

    /**
     * @var DataGrid\RowParser
     */
    private $rowParser;

    /**
     * @var bool
     * @var bool
     */
    private $usePaginate = true;

    /**
     * @var array
     */
    private $columns = array();

    /**
     * @var Result|\Iterator|array
     */
    private $results;

    /**
     * @var Paginate|\Iterator|array
     */
    private $paginate;

    /**
     * @var int
     */
    private $totalResults = null;

    private $actionsLabel = 'Actions';

    private $actions = array();

    /**
     * @param RowParser $rowParser
     */
    public function __construct(RowParser $rowParser){
        $this->rowParser = $rowParser;
    }

    public function getRowParser(){
        $this->rowParser->setRow($this->getResults()->current());
        return $this->rowParser;
    }

    /**
     * Set if use pagination or not
     *
     * There is not pagination logic, used only for generation pagination in template
     *
     * @param bool $usePaginate
     * @return DataGrid
     */
    public function setUsingPagination($usePaginate = true){
        if(!is_bool($usePaginate)){
            throw new UnexpectedTypeException($usePaginate, 'boolean');
        }
        $this->usePaginate = $usePaginate;

        return $this;
    }

    /**
     * Set data for rows
     *
     * @param \W3build\PaginateBundle\Result|\Iterator|array $results
     * @throws DataGrid\Exception\NotImplemendInterfaceException
     * @throws DataGrid\Exception\UnexpectedTypeException
     * @return DataGrid
     */
    public function setResults($results){
        if($results instanceof Paginate){
            if(!$this->isPaginationEnabled()){
                trigger_error('Pagination is disabled, but Paginate result given');
            }
            $this->paginate = $results;
        }
        else {
            if(!is_object($results) && !is_array($results)){
                throw new UnexpectedTypeException($results, 'object or array');
            }
            elseif(is_object($results) && !($results instanceof \Iterator)){
                throw new NotImplemendInterfaceException($results, 'Iterator Interface');
            }

            $this->results = $results;
        }

        return $this;
    }

    public function getResults(){
        if(!$this->results && $this->isPaginationEnabled()){
            $this->results = $this->paginate->getResult();
        }

        return $this->results;
    }

    public function setColumns(array $columns){
        $this->columns = $columns;
    }

    /**
     * Add data grid columns
     *
     * @param ColumnAbstract $column
     * @return DataGrid
     */
    public function addColumn(ColumnAbstract $column){
        $column->setDataGrid($this);
        $this->columns[] = $column;

        return $this;
    }

    /**
     * Return configured columns
     *
     * @return array
     */
    public function getColumns(){
        return $this->columns;
    }

    /**
     * Num rows
     *
     * @return int
     */
    public function getTotalResults(){
        if($this->totalResults === null){
            if($this->isPaginationEnabled()){
                $this->totalResults = $this->paginate->getResult()->getTotalResults();
            }
            else {
                $this->totalResults = count($this->results);
            }
        }
        return $this->totalResults;
    }

    /**
     *
     * @return bool
     */
    public function isPaginationEnabled(){
        return $this->usePaginate;
    }

    public function addAction(ActionAbstract $action){
        $action->setDataGrid($this);
        $this->actions[] = $action;

        return $this;
    }

    public function getActions(){
        return $this->actions;
    }

    public function setActionLabel($actionLabel){
        $this->actionsLabel = $actionLabel;

        return $this;
    }

    public function getActionLabel(){
        return $this->actionsLabel;
    }

} 