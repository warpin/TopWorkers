<?php
 
class DB_CONNECT {
 
    function __construct() {
        $this->connect();
    }
 
    function __destruct() {
        $this->close();
    }
 
    function connect() {
        require 'db_config.php';
        $con = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD) or die(mysql_error());
        mysql_query("SET NAMES 'cp1251'");
        mysql_query("SET CHARACTER SET 'cp1251'");
        $db = mysql_select_db(DB_DATABASE) or die(mysql_error()) or die(mysql_error());
        return $con;
    }
 
    function close() {
        mysql_close();
    }
 
}
 
?>