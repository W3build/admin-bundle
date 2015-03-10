<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 12.9.14
 * Time: 15:05
 */

namespace W3build\Admin\DataGridBundle\DataGrid\Exception;


class NotImplemendInterfaceException extends \RuntimeException {

    public function __construct($value, $expectedType)
    {
        parent::__construct(sprintf('Expected argument implements "%s" interface, "%s" given', $expectedType, is_object($value) ? get_class($value) : gettype($value)));
    }

} 