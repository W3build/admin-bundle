<?php
/**
 * Created by PhpStorm.
 * User: Jahodal
 * Date: 12.9.14
 * Time: 15:05
 */

namespace W3build\Admin\DataGridBundle\DataGrid\Exception;


class UnexpectedTypeException extends \RuntimeException {

    public function __construct($value, $expectedType)
    {
        parent::__construct(sprintf('Expected argument of type "%s", "%s" given', $expectedType, is_object($value) ? get_class($value) : gettype($value)));
    }

} 