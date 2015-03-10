<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 12.9.14
 * Time: 15:12
 */

namespace W3build\Admin\DataGridBundle\DataGrid\Exception;


class BadConfigurationException extends \RuntimeException {

    public function __construct($message)
    {
        parent::__construct($message);
    }

} 