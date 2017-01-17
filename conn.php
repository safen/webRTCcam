<?php 
    class connection {
        public function connect(){
            try {
            $DB_Server="localhost";
            $DB_Name="id551748_smartcam";
            $DB_User="id551748_smartcam";
            $DB_Password="safen12345";    
            $co = new PDO("mysql:host=$DB_Server;dbname=$DB_Name", "$DB_User", "$DB_Password");
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $co->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
            return $co;
            }catch (PDOException $e) {
            print "Error!: " .$e->getMessage(). "<br/>";
            die();
            }
        }
}