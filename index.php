<?php
session_start();  
?>
<script>
if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<link rel="stylesheet" href="style.css">

<style>
    .loginform{
        border: 6px solid black;
        width:30%;
        height:20%;
        display:block;
        margin:auto;
        margin-top:10%;
        text-align:center;
        padding-top:5%;
    }
    @media  screen and (max-width: 1000px) {
		  
		  .loginform{
		      width:70% !important;
		      height:60%;
		  }
		  input,button,label{
		      width:50%;
		      height:20%;
		      font-size:40px;
		  }
		  
		     
		 }
</style>

<div class='loginform'>
<form action="index.php" method="post">
    <label for='uname'>Username:</label>
    <input type='text' pattern="[a-zA-Z0-9!@#$%^*_|]{0,100}" placeholder='Enter Username' name ='uname' required>
    <br>
    <label for="psw">Password:</label>
    <input type="password" pattern="[a-zA-Z0-9!@#$%^*_|]{0,100}" placeholder="Enter Password" name="psw" required>
    <br><br>
    <button type="submit" name='choice' value='1'>Login</button>
    <button type="submit" name='choice' value='2'>Sign up</button>
</form>


</div>


<?php
require "Functions.php";
$db= new databasecalls();
if(isset($_POST['choice'])){
    $choice=$_POST['choice'];
}

if (isset($_POST['uname'])&&isset($_POST['psw'])&&$choice==1){
$username=$_POST['uname'];
$password=$_POST['psw'];
$arr=$db->getusers($username);
//print_r($arr);
if (!empty($arr)){
if ($arr[0][0]==$username&&$arr[0][1]==$password){
    $_SESSION['user']=$arr[0][2];
    
}else{
    unset($_SESSION['user']);
}
}else{
    unset($_SESSION['user']);
}


}

if (isset($_POST['uname'])&&isset($_POST['psw'])&&$choice==2){
$username=$_POST['uname'];
$password=$_POST['psw'];

$arr=$db->inputnewaccount($username,$password);

if ($arr[0]==true){

    $_SESSION['user']=$arr[1];
}else{
    unset($_SESSION['user']);
}


}


if (isset($_SESSION['user'])) {
   //echo 'logged in';
   if (isset($username)){
   echo "<script type='text/javascript'>location.href = 'browse.php';</script>";
   }
 } else {
   //echo 'not logged in';
   echo '<script>alert("not logged in")</script>';
 }




?>
