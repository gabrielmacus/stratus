<?php

/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 26/12/2017
 * Time: 11:18 AM
 */
class BeanList implements IView
{

    protected $dataToPrint;


    public function __construct(array $printables)
    {

        $dataToPrint = [];

        foreach ($printables as $printable){

            if(is_a($printable,"IPrintable"))
            {
                $dataToPrint[]=$printable->printSerialize();
            }

        }
        $this->dataToPrint = $dataToPrint;
    }

    public function getHTML()
    {

        $core = new Dwoo\Core();

        $templateData = [];

        if(count($this->dataToPrint) > 0)
        {
            $templateData["head"] = array_keys(reset($this->dataToPrint));

            $templateData["items"] = $this->dataToPrint;
        }


        $html =  $core->get(dirname(__FILE__)."/list.tpl", $templateData);



        return $html;

    }

}