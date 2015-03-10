<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 12.9.14
 * Time: 15:32
 */

namespace W3build\Admin\DataGridBundle\Tests;

require_once 'DataGrid.php';
//require_once 'DataGrid/Container.php';
require_once 'DataGrid/Exception/UnexpectedTypeException.php';

//use W3build\Admin\DataGridBundle\DataGrid\Container;
use W3build\Admin\DataGridBundle\DataGrid;
use W3build\Admin\DataGridBundle\DataGrid\Exception\UnexpectedTypeException;

class DataGridTest extends \PHPUnit_Framework_TestCase {

    public function testPaginateIsDefaultDisabled(){
        $containerMock = $this->getMock('W3build\Admin\DataGridBundle\DataGrid\Container');

        $dataGrid = new DataGrid($containerMock);

        $this->assertFalse($dataGrid->isPaginationEnabled());
    }

    public function testSetIdentifierCallContainerRegister(){
        $containerMock = $this->getMock('W3build\Admin\DataGridBundle\DataGrid\Container', array('register'));
        $containerMock->expects($this->once())
                      ->method('register');

        $dataGrid = new DataGrid($containerMock);

        $dataGrid->setIdentifier('test');
    }

    public function testSetIdentifierCallContainerRegisterWithSameParam(){
        $param = 'sdfsafěš1578';

        $containerMock = $this->getMock('W3build\Admin\DataGridBundle\DataGrid\Container', array('register'));
        $containerMock->expects($this->once())
            ->method('register')
            ->with($param);

        $dataGrid = new DataGrid($containerMock);

        $dataGrid->setIdentifier($param);
    }

    public function testPaginateSettingThrowExceptionWhenNoBooleanParam(){
        $containerMock = $this->getMock('W3build\Admin\DataGridBundle\DataGrid\Container');

        $dataGrid = new DataGrid($containerMock);

        try {
            $dataGrid->setUsingPagination('test');
        }
        catch (UnexpectedTypeException $e){
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testPaginateSettingNotThrowExceptionWhenBooleanParam(){
        $containerMock = $this->getMock('W3build\Admin\DataGridBundle\DataGrid\Container');

        $dataGrid = new DataGrid($containerMock);

        try {
            $dataGrid->setUsingPagination(true);
        }
        catch (UnexpectedTypeException $e){
            $this->fail('An exception has been raised.');
        }

        return;
    }

    public function testPaginateSettingIsSetToTrueValue(){
        $containerMock = $this->getMock('W3build\Admin\DataGridBundle\DataGrid\Container');

        $dataGrid = new DataGrid($containerMock);
        $dataGrid->setUsingPagination(true);

        $this->assertTrue($dataGrid->isPaginationEnabled());
    }

    public function testPaginateSettingIsNotSetToTrueValue(){
        $containerMock = $this->getMock('W3build\Admin\DataGridBundle\DataGrid\Container');

        $dataGrid = new DataGrid($containerMock);
        $dataGrid->setUsingPagination(false);

        $this->assertFalse($dataGrid->isPaginationEnabled());
    }

}