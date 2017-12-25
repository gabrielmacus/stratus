<?php

/**
 * Created by PhpStorm.
 * User: Puers
 * Date: 22/12/2017
 * Time: 19:24
 */


class Bean  implements ArrayAccess, JsonSerializable
{
    use Magic;
    public function __construct()
    {
    }

    public function offsetExists($offset)
    {
       return isset($this->$offset);
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetSet($offset, $value)
    {

        $this->$offset = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }

    function jsonSerialize()
    {
        $json = [];
       foreach ($this as  $k=>$v)
       {
               $json[$k]=$v;
       }

       return $json;
    }



}