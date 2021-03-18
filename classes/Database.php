<?php
class Database{
    private $db_host = 'localhost';
    private $db_name = 'FS_api';
    private $db_username = 'root';
    private $db_password = '';
    
    public function dbConnection(){
        
        try{
            $con = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name,$this->db_username,$this->db_password);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $con;
        }
        catch(PDOException $e){
            echo "Connection error ".$e->getMessage(); 
            exit;
        }
          
    }
}
