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

    protected  function beforeSave(Bean &$bean)
    {

    }

    protected  function afterSave(Bean &$bean)
    {

    }

    function save(Bean &$bean)
    {


        $this->mongoConnection->connect();

        $collection = $this->mongoConnection->client()->beans;

        $bean->validate();

        $data = $bean->jsonSerialize() + ["_type"=>get_class($bean)];

        $this->beforeSave($bean);

        if(!$collection->save($data))
        {
            throw new Exception("bean.error.save");
        }

        $bean->_id = $data["_id"];

        $this->afterSave($bean);

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
                    //$beanFromArray;
                    $results[$id][$v]= $k;
                }

                $results[$id]["_bean"]=$beanFromArray;

            }


        }

        if($level<$maxLevel && count($associatedBeans) > 0)
        {

            return $this->readAssociations($parentBeans,$maxLevel,$associatedBeans,$results,$level);


        }



        foreach ($results as $i=>$j)
        {

            $b = $j["_bean"];

            unset($j["_bean"]);

            foreach ($j as $k=>$l)
            {

                if(!empty($results[$l]))
                {



                    if(empty($results[$l]["_bean"][$k]))
                    {
                        $results[$l]["_bean"][$k]=[];
                    }
                    $arr = $results[$l]["_bean"][$k];

                    $arr[strval($b["_id"])] = $b;

                    $results[$l]["_bean"][$k]=$arr;

                }
            }

        }


        foreach ($results as $i=>$j)
        {

            $b = $j["_bean"];

            unset($j["_bean"]);

            foreach ($j as $k=>$l)
            {


                if(!empty($parentBeans[$l]))
                {

                    if(empty($parentBeans[$l][$k]))
                    {
                        $parentBeans[$l][$k]=[];
                    }

                    $arr = $parentBeans[$l][$k];

                    $arr[strval($b["_id"])] = $b;

                    $parentBeans[$l][$k]=$arr;


                }
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


     static function BeanFromArray(Array $array)
    {



            $bean = new $array["_type"]();

            foreach ($array as $k=>$v)
            {
                $bean[$k]=$v;
            }

            return $bean;

    }

}