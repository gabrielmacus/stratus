<?php

/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 27/12/2017
 * Time: 0:08
 */
class BeanController
{

    use Magic;

    protected $DAO;

    /**
     * BeanController constructor.
     * @param $DAO
     */
    public function  __construct($DAO)
    {
        $this->DAO = $DAO;
    }


    function create()
    {

    }

    function read()
    {

    }

    function update()
    {

    }

    function delete()
    {

    }

}