<?php
include './conn.php';
 class command {
    public function execute($sql){      
        try {
        $obj=  new connection(); 
        $db=$obj->connect();
        $q=$db->prepare($sql);
        $q->execute();
        return $db->lastInsertId();
        }catch (Exception $exc) {
            echo $exc->getMessage();
            
        }              
    }
    
    public function select($sql){
        try {
        
        $obj=  new connection(); 
        $stmt = $obj->connect()->query($sql);
      
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            die();
        }       
    }          
}
 
//http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers
 //$obj= new command();
//echo $obj->execute("INSERT INTO `login`( `user_name`, `password`) VALUES ('a','z'); "); 
// $r=$obj->select("SELECT count(*)as user_name from login");
// foreach($r as $row) {
//    echo $row['user_name'].' : ';//.$row['password']. '<br>';
//}



 
 
