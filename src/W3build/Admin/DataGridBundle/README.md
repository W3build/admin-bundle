W3build Admin DataGrid Bundle
==============================

This document contains information how to use this component bundle.
This component show how to use with default settings and configuration.

If you want some optional configuration look on article [Configuration options].

1) Application design
---------------------

Application have following components:

 * **DataGrid** - Basic service, you will acces to this service (`DataGrid.php`)
 * **TwigExtension** - Twig extension to render data to Twig template (`DataGrid/TwigExtension.php`)
 * **Columns** - Allows access to row data for specific columns and generate DataGrid headers (`DataGrid/Column/Abstract.php`)
 * **Actions** - Builder to generate Action buttons (`DataGrid/Action/ActionAbstract.php`)
 * **Container** - Registered instances container - Allow store instance for next time usage (`DataGrid/Container.php`)


2) DataGrid
-----------

### Create instance
Instance of DataGrid is available form ServiceContainer by following service ID:

    admin.data_grid

If you want access data_grid from your Controller you can do it with following command:

    $this->get('admin.data_grid');

When you call instance from ServiceContainer you obtain new instance with default options

### Specify column
To specify column to dataGrid you must use following command (`addColumn(ColumnAbstract $column)`):

    $idColumn = new \W3build\Admin\DataGridBundle\DataGrid\Column('idColumn');
    $idColumn->setLabel('Id')
             ->setData('$row->id');

    $dataGrid = $this->get('dataGrid');
    $dataGrid->addColumn($idColumn);

3) Columns
----------
Column is specify by column instance. Most uses are Action column (`DataGrid/Column/Action.php`) and Basic (`DataGrid/Column.php`)

All column types have this configuration methods:

 * **SetLabel(string $label)** - Set label name displayed in header - Label names is translated if translate is configured
 * **SetData(string $data)** - Data displayed in row column (more in [Acess to row data])
 * **setCustomDataParser(fucntion $parserFunction)** - Custom row data parser function (more in [Custom data parser]

Basic column can be configured with following command:

    $idColumn = new \W3build\Admin\DataGridBundle\DataGrid\Column('idColumn');
    $idColumn->setLabel('Id')
             ->setData('$row->id');

Optionaly by constructor:

    $idColumn = new \W3build\Admin\DataGridBundle\DataGrid\Column('idColumn', 'Id', '$row->id');
