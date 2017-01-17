<?php

include_once './cmd.php';
include_once './sec.php';

session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: ./index.php");
}
$cmd=new command();
$MD5=new encryption();
if(isset($_POST['add'])){
    $name=$_POST['nm'];
    $pass=$MD5->encrypt($_POST['pass']);
    $email=$_POST['email'];
    $cmd->execute("INSERT INTO admin (Name,Email,Password) VALUES('$name','$email','$pass') ");
}
if(isset($_POST['delete'])){
 $uid=$_POST['uid'];
 $cmd->execute("DELETE FROM admin WHERE admin_id=$uid ");
}




?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="./st.css" />
    </head>
    <body>
        <div class="nav" >
            <form method="post" style="float: right">
            
        <?php
                
                if(isset($_POST['loguot'])){
                    echo session_unset();
                }
                ?>
            
            <input type="submit" name="loguot" value="logout" style="margin-top:15%;   ">
        </form>
        </div>
        
        <br>
        
        <div class="main">
            <center>
                <table border="1" style="margin-top: 3%;"> 
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
                <script>
                    function chk(ch){
                     document.getElementById('uid').value = ch.getAttribute("name");
                    } 
                </script>
                
                <?php
                 $r=$cmd->select("SELECT * FROM admin");    
                  foreach($r as $row) {
                      
                     $id= $row['admin_id'];
                     $n= $row['Name'];
                     $e= $row['Email'];
                     
                      echo "<tr>
                    <td><input type=\"checkbox\" name=\"$id\" value=\"checked\" id=\"aid\" onclick=\"chk(this)\" /></td>
                    <td>$id</td>
                    <td>$n</td>
                    <td>$e</td>
                </tr>";
                      
                      
                    
                    }
                ?>
                
                
                
            </table>
                <br>
                <form method="post">
                    <input type="hidden" id="uid"  value="0" name="uid" />
                    <input type="text" placeholder="Name"  name="nm"/><br>
                    <input type="text" placeholder="Email" name="email" /><br>
                    <input type="text" placeholder="password" name="pass" /><br>
                    <input type="submit" value="Add" name="add"/>
                   <input type="submit" value="Delete" name="delete">
                    
                </form>
                </center>
        </div>
        
        
        <div class="hm" >
            <ul>
                <li><a class="active" href="./Admin.php">Admins</a></li>
                <li><a href="./users.php">Users</a></li>
                <li><a href="./cameras.php">Cameras</a></li>
            </ul>
        </div>
        
    </body>
</html>
