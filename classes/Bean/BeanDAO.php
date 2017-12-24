<?php

/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 22/12/2017
 * Time: 23:18
 */


class BeanDAO
{
    protected  $mongoConnection;

    /**
     * BeanDAO constructor.
     * @param $mongoConnection
     */
    public function __construct($mongoConnection)
    {
        $this->mongoConnection = $mongoConnection;
    }


    function save(Bean &$bean)
    {
        $this->mongoConnection->connect();

        $collection = $this->mongoConnection->client()->beans;

        $data = $bean->jsonSerialize() + ["_type"=>get_class($bean)];

        if(!$collection->save($data))
        {
            throw new Exception("bean.error.save");
        }



    }

    function readAssociations(Array &$parentBeans, $maxLevel=3,$beans=false,Array &$results=[],$level=0)
    {
        if(!$beans)
        {
            $beans = $parentBeans;
        }

        $level++;

        $this->mongoConnection->connect();

        $collection=$this->mongoConnection->client()->beans;

        $ids = [];


        foreach ($beans as $bean)
        {

            if(is_a($bean,"Bean") && !empty($bean["_id"]))
            {
                $ids[]=$bean["_id"];
            }

        }

        $associatedIds=[];
        $associationsMap=[];
        $associatedBeans=[];

        $associations = $collection->find
        (
            [
                '_type'=> "Association",
                "id1"=>['$in'=> $ids]
            ]
        );
        foreach ($associations as $key => $value)
        {

            $associationsMap[strval($value["id2"])][strval($value["id1"])]=$value["_name"];

            $associatedIds[]=$value["id2"];

        }

        $associatedCursors = $collection->find(['_id'=>['$in'=>$associatedIds]]);

        foreach ($associatedCursors as $key => $value)
        {
            $id=strval($value["_id"]);

            $beanFromArray = BeanDAO::BeanFromArray($value);

            $associatedBeans[$id] =  $beanFromArray;

            if(!empty($associationsMap[$id]))
            {
                foreach ($associationsMap[$id] as $k=>$v)
                {

                    $results[$id]= ["bean"=>$beanFromArray,"group"=>$v,"parent"=>$k];
                }

            }


        }

        if($level<$maxLevel && count($associatedBeans) > 0)
        {

            return $this->readAssociations($parentBeans,$maxLevel,$associatedBeans,$results,$level);


        }



        foreach ($results as $k=>$v)
        {



            if(!empty($results[$v["parent"]]))
            {

                if(empty($results[$v["parent"]]["bean"][$v["group"]]))
                {
                    $results[$v["parent"]]["bean"][$v["group"]]=[];
                }

                $arr=$results[$v["parent"]]["bean"][$v["group"]];

                $arr[strval($v["bean"]["_id"])]=$v["bean"];

                $results[$v["parent"]]["bean"][$v["group"]]=$arr;

             //   unset($results[$k]);
            }
        }

      foreach ($results as $k=>$v)
        {

            if(!empty($parentBeans[$v["parent"]]))
            {
                if(empty($parentBeans[$v["parent"]][$v["group"]]))
                {
                    $parentBeans[$v["parent"]][$v["group"]]=[];
                }

                $arr = $parentBeans[$v["parent"]][$v["group"]];

                $arr[strval($v["bean"]["_id"])] = $v["bean"];

                $parentBeans[$v["parent"]][$v["group"]] = $arr;


            }
        }



    }

    function read(Bean $bean )
    {
        //Results feteched
        $results = [];

        $this->mongoConnection->connect();

        $collection=$this->mongoConnection->client()->beans;

        //Query array
        $query = $bean->jsonSerialize();

        if(get_class($bean) != "Bean")
        {
            $query = $query  + ["_type"=>get_class($bean)];
        }

        $cursor = $collection->find($query);

        foreach ($cursor as $k=>$v)
        {
            $results[strval($v["_id"])]= BeanDAO::BeanFromArray($v);
        }



        return $results;

    }

    function delete(Bean $bean)
    {
        $this->mongoConnection->connect();

        $collection = $this->mongoConnection->client()->beans;

        $data = $bean->jsonSerialize() + ["_type"=>get_class($bean)];

        if(!$collection->remove($data))
        {
            throw new Exception("bean.error.delete");
        }

    }

    function associate(Array $associations)
    {

        $this->mongoConnection->connect();

        $collection=$this->mongoConnection->client()->beans;

        $_associations=[];

        foreach ($associations as $value)
        {
            $association = new Association();

            $association->type1 = get_class($value[0]);

            $association->id1 = $value[0]->_id;

            $association->type2 = get_class($value[1]);

            $association->id2 = $value[1]->_id;

            $association->_name=$value[2];

            $association->_type = get_class($association);

            if(!empty($value[3]) && is_array($value[3]))
            {
                $association["extra"] = $value[3];
            }

            $_associations[] = $association->jsonSerialize();

        }

        $collection->batchInsert($_associations);

    }


    private static function BeanFromArray(Array $array)
    {

            $bean = new $array["_type"]();

            foreach ($array as $k=>$v)
            {
                $bean[$k]=$v;
            }

            return $bean;

    }

}