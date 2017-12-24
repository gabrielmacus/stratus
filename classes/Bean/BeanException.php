<?php

/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 22/12/2017
 * Time: 23:07
 */
class BeanException extends Exception
{
    public function __construct($message = "", $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


}