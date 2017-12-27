<?php

/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 26/12/2017
 * Time: 20:51
 */
interface IList
{

    /**
     * IView constructor.
     * @param array $printables IPrintable objects array
     */
    public function __construct(array $printables);


    /**
     * Is called every loop when processing data to print
     *
     * @param IPrintable $printable
     * @return mixed
     */
    public function onProcess(IPrintable $printable);

}