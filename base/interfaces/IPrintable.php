<?php

/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 26/12/2017
 * Time: 11:20 AM
 */
interface IPrintable
{
    /**
     * Specify data which should be printed to a view. Should be implemented in module model that is intend to be printed
     *
     * @return array
     */
     public function printSerialize();
}