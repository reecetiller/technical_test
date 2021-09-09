<?php
session_start(); 
if (isset($_SESSION['user'])) {
   //echo $_SESSION['user'];
 } else {
   die("Not logged in, please log in and try again");
 }

?>


<?php 

include("header.html");
require "Functions.php";
$db= new databasecalls();
if (isset($_POST['remove'])){
    $heroidremove=$_POST['remove'];
    $db->removefromuser($heroidremove,$_SESSION['user']);
}

$arr=$db->getherosfromdatabase($_SESSION['user']);
$pieces = explode("/", $arr);
//print_r($pieces);
$html=2;
$names=array();
for ($i=0;$i<count($pieces);$i++){
$names[$i]=$db->retrievesuperhero($pieces[$i]);
$names[$i]=$db->make_blocks($names[$i],$html);
}

?>

<script>
$(function(){

  
  
	<?php
	for ($i=0;$i<count($names);$i++){
	    
  echo '$("#'.$names[$i].'").click(function(){
	$("#'.$names[$i].'1").show();
    });
		    
  $("#'.$names[$i].'close").click(function(){
    $("#'.$names[$i].'1").hide();
	});';
	}
  ?>

});
</script>

