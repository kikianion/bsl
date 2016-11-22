<?php

class DB
{
    //settings
    public $host = "localhost";
    public $user = "root";
    public $pass = "maskandar123";
    public $db = "bsampahlmg";

    public function getPDOConnection()
    {
        try {
            $pdo_conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $pdo_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo_conn;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        }
    }
}

?>