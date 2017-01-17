<?php
session_start();
include_once './cmd.php';
include_once './sec.php';
$cmd=new command();
$MD5=new encryption();

if(isset($_SESSION['admin_id'])){
    header("Location: ./Admin.php");
}else if(isset($_SESSION['user_id'])){
    header("Location: ./User.php");
}else if(isset($_SESSION['camera'])){
    header("Location: ./camera.php");
}

if(isset($_POST['login'])){
    $user=$_POST['user'];
    $pass=$_POST['pass'];
    $typ=$_POST['typ'];
    $l=0;
    
    if($_POST['typ']=="Admin"){
         
        $r=$cmd->select("SELECT * FROM admin WHERE Email='$user' ");
        foreach($r as $row) {

            $p=$row["Password"];
            if($pass== $MD5->decrypt($p)){
                $l=1;
            }
        }
        if($l==1){
            $_SESSION['admin_id']=$_POST['user'];
            header("Location: ./Admin.php");
        }
        
    }else if($_POST['typ']=="User") {
        $r=$cmd->select("SELECT * FROM users WHERE Email='$user' ");
        foreach($r as $row) {
            $p=$row["Password"];
            if($pass== $MD5->decrypt($p)){
                $l=1;
            }
        }
        if($l==1){
        $_SESSION['user_id']=$_POST['user'];
            header("Location: ./User.php");
        }
    }else if($_POST['typ']=="Camera") {
        $r=$cmd->select("SELECT * FROM cam WHERE camn_name='$user' ");
        foreach($r as $row) {
            $p=$row["password"];
            if($pass== $MD5->decrypt($p)){
                $l=1;
            }
        }
        if($l==1){
        $_SESSION['camera']=$_POST['user'];
            header("Location: ./camera.php");
        }
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="./st.css" />
        
        
    </head>
    <body>
        <div class="nav"></div>
        
           <center>
               <form method="POST" style="margin-top:10%; " >
                   <input type="text" placeholder="Camera Name / Email" name="user"><br>
                   <input type="password" placeholder="Password" name="pass"><br>
                   <center>
                   <select style="width:173px; " name="typ">
                       <option>Admin</option>
                       <option>User</option>
                       <option>Camera</option>
                   </select>
                   </center>
                   <br>
                   <input type="submit" name="login" value="Login" style="width:173px; ">
               </form>
           </center>
        
        
    </body>
</html>
