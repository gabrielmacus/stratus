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
    protected $post;
    protected $get;

    /**
     * BeanController constructor.
     * @param $DAO
     * @param $post $_POST array
     * @param $get  $_GET array
     */
    public function __construct(BeanDAO $DAO, $post, $get)
    {
        $this->DAO = $DAO;
        $this->post = $post;
        $this->get = $get;
    }


    function create()
    {

     $bean = BeanDAO::BeanFromArray($_POST);

     unset($bean["_id"]);

     $this->DAO->save($bean);

        echo  json_encode($bean);
    }
    function save()
    {

        if(!empty($_POST["_id"]))
        {
            $this->update();
        }
        else
        {
            $this->create();
        }


    }

    function read()
    {



        $bean = BeanDAO::BeanFromArray($_GET);

        $printables = $this->DAO->read($bean);

        $listType ="{$bean["_type"]}List";

        $list = new $listType($printables);

        $layoutType = "{$bean["_type"]}Layout";

        $layout = new $layoutType("",[],$list);


        echo $layout->getHTML();
    }

    function update()
    {
        $id = (!empty($_POST["_id"]))?new MongoId($_POST["_id"]):false;

        if(!$id)
        {
            throw new BeanException("bean.error.update.idNotSpecified");
        }
        else
        {
            $bean = new Bean();

            $bean->_id = $id;

            $result = $this->DAO->read($bean);

            if(count($result)==0)
            {
                throw new BeanException("bean.error.update.notExists");
            }

            $bean = reset($result);
        }

        foreach ($_POST as $k=>$v)
        {
            $bean[$k]=$v;
        }

        $bean->_id = $id;

        $this->DAO->save($bean);

        echo  json_encode($bean);


    }

    function delete()
    {

    }

}