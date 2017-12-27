<?php

/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 26/12/2017
 * Time: 20:51
 */
interface IView
{

    /**
     * IView constructor.
     * @param array $printables IPrintable objects array
     */
    public function __construct(array $printables);

    public function getHTML();

}