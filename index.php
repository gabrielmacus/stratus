<?php
/**
 * Created by PhpStorm.
 * User: Gabriel
 * Date: 22/12/2017
 * Time: 02:24 PM
 */

include "classes/autoload.php";

$mongoConnection = new MongoConnection("stratus");

$mongoConnection->connect();

$DAO = new BeanDAO($mongoConnection);

/*
$company = new Company();

$company->name="Advertis";

$DAO->save($company);

$company = new Company();

$company->name="Lemon Data";

$DAO->save($company);

$company = new Company();

$company->name="Grandi Y Asociados";

$DAO->save($company); */

//Advertis
/*
$company = new Company();
$company->_id = new MongoId("5a3ec7b9145f8bc414000042");


$employee = new Employee();
$employee->_id = new MongoId("5a3ec861145f8bc414000045");

$associations[]=[$company,$employee,"employees"];

$employee = new Employee();
$employee->_id = new MongoId("5a3ec861145f8bc414000046");

$associations[]=[$company,$employee,"employees"];


//Lemon Data
$company = new Company();
$company->_id = new MongoId("5a3ec7b9145f8bc414000043");


$employee = new Employee();
$employee->_id = new MongoId("5a3ec861145f8bc414000048");

$associations[]=[$company,$employee,"employees"];

$employee = new Employee();
$employee->_id = new MongoId("5a3ec861145f8bc414000049");

$associations[]=[$company,$employee,"employees"];

//Grandi
$company = new Company();
$company->_id = new MongoId("5a3ec7b9145f8bc414000044");

$employee = new Employee();
$employee->_id = new MongoId("5a3ec861145f8bc41400004a");

$associations[]=[$company,$employee,"employees"];

$DAO->associate($associations);
*/


/*
$employee = new Employee();
$employee->name="Gabriel Macus";
$employee->surname="Macus";

$DAO->save($employee);

$employee = new Employee();
$employee->name="Pitu";
$employee->surname="Vilotta";

$DAO->save($employee);

$employee = new Employee();
$employee->name="John";
$employee->surname="Doe";

$DAO->save($employee);

$employee = new Employee();
$employee->name="Jane";
$employee->surname="Doe";

$DAO->save($employee);

$employee = new Employee();
$employee->name="Rocio";
$employee->surname="Duré";

$DAO->save($employee);

$employee = new Employee();
$employee->name="Foo";
$employee->surname="Bar";

$DAO->save($employee);
*/

/*
$company = new Company();
$company->_id = new MongoId("5a3ec7b9145f8bc414000042");

$DAO->associate($company,)
*/



/*
$gps = new GPS();
$gps->_id =new MongoId("5a3f327e145f8bc41400005b");

$map = new Map();
$map->_id = new MongoId("5a3f3669145f8bc41400005d");

$DAO->associate([
    [$gps,$map,"maps",["caption"=>"Caption demo"]]

]);
*/


/*
$car  = new Car();

$car->_id=new MongoId("5a3f20d2145f8bc414000059");



$gps = new GPS();
$gps->_id = new MongoId("5a3f327e145f8bc41400005b");

$DAO->associate([[$car,$gps,"accesories"]]);
*/
/*
$car  = new Car();

$car->_id=new MongoId("5a3f20d2145f8bc414000059");


$employee = new Employee();
$employee->_id = new MongoId("5a3ec861145f8bc414000045");

$DAO->associate([[$employee,$car,"cars"]]);*/





$c = new Company();
$c = $DAO->read($c);
$DAO->readAssociations($c,4);

echo json_encode($c);