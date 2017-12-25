<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 22/12/2017
 * Time: 02:24 PM
 */


include "autoload.php";


$mongoConnection = new MongoConnection("stratus");

$mongoConnection->connect();

$DAO = new BeanDAO($mongoConnection);