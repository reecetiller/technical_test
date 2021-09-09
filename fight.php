<?php
session_start(); 
if (isset($_SESSION['user'])) {
 } else {
   die("Not logged in, please log in and try again");
 }

?>


<?php 

include("header.html");
require "Functions.php";
$db= new databasecalls();
?>
<p class='Villain_Text' style='text-align:center;'>Pick a Villain to Fight</p>
<?php

if (isset($_POST['fight'])){
    $fight=$_POST['fight'];
    $var= $db->fight($fight,$_SESSION['user']);

    if ($var==0){
        echo '<p style="text-align:center;font-size:40px;">Win<p>';
    }else{
        echo '<p style="text-align:center;font-size:40px;">Lose</p>';
    }
}
$arr=$db->getherosfromdatabase(99999999);
$pieces = explode("/", $arr);
$html=3;
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
