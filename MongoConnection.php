<?php

/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 22/12/2017
 * Time: 02:22 PM
 */
class MongoConnection
{

    protected $user;
    protected $password;
    protected $db;
    protected $port;

    /**
     * MongoConnection constructor.
     * @param $user
     * @param $password
     * @param $db
     * @param $port
     */
    public function __construct($user, $password, $db, $port)
    {
        $this->user = $user;
        $this->password = $password;
        $this->db = $db;
        $this->port = $port;
    }

    public function __get( $key )
    {
        return $this->$key;
    }

    public function __set( $key, $value )
    {
        $this->$key = $value;
    }



}

