<?php

class Database {

    private $cn  ;
    private $res ; 
    
    
    private static $instance;

    private function __construct(){}

    public static function getInstance() {
	    if(! self::$instance) self::$instance = new Database;
	    return self::$instance;
    }
    
  
    public function escape($str) {
	    return mysqli_escape_string($this->cn, $str);
    }
  
    
    public function query($q) {
	if(!$this->cn) {
	    $this->cn = mysqli_connect("localhost", "root", "", "spotted");
	}
	
	$this->res = mysqli_query($this->cn, $q);
	if(mysqli_error($this->cn) != "")
	    echo 'ERROR CONSULTA: ' . mysqli_error($this->cn);
     }	

}