<?php

class databasecalls {
    public $host ='localhost';
    public $dbname = 'id17532304_reece';
    public $username = 'id17532304_reece123';
    public $password = 'p@2T6]+88ZAFpt)n';
    
function fight($Vid,$userid){
    $db= new databasecalls();
    $arr=$db->getherosfromdatabase($userid);
    $pieces = explode("/", $arr);
    $arrayoffighters=array();
    $herotot=0;
    for ($i=0;$i<count($pieces);$i++){
        $arrayoffighters[$i]=$db->retrievesuperhero($pieces[$i])->powerstats;
        $herotot=$herotot+$arrayoffighters[$i]->intelligence+$arrayoffighters[$i]->strength+$arrayoffighters[$i]->speed+$arrayoffighters[$i]->durability+$arrayoffighters[$i]->power+$arrayoffighters[$i]->combat;
    }
    $villianstats=$db->retrievesuperhero($Vid)->powerstats;
    $villiantot=$villianstats->intelligence+$villianstats->strength+$villianstats->speed+$villianstats->durability+$villianstats->power+$villianstats->combat;
    $villiantot= $villiantot*5;
    if ($villiantot>$herotot){
        return 1;
    }else{
        return 0;
    }
    
}



function removefromuser($idtoremove,$userid){
    $db= new databasecalls();
    $arr=$db->getherosfromdatabase($userid);
    if (strpos($arr, $idtoremove) !== false){
        $pieces = explode("/", $arr);
        if (count($pieces)>1){
        array_splice($pieces, array_search($idtoremove, $pieces ), 1);
        $new='';
        for ($i=0;$i<count($pieces);$i++){
            if($i!=(count($pieces)-1)){
            $new.=$pieces[$i]."/";
            }else{
            $new.=$pieces[$i];
            }
        }
        
        $conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
        $sql= "UPDATE Heros SET Hero_Id='".$new."' WHERE user_id=".$userid.";";
        $conn->exec($sql);
        }
    }
}

function addtouserteam($idtoadd,$userid){
    $db= new databasecalls();
    $arr=$db->getherosfromdatabase($userid);
    if (strpos($arr, $idtoadd) !== false){
    }else{
        $pieces = explode("/", $arr);
        if (count($pieces)<5){
        $arr.="/".$idtoadd;
        $conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
        $sql= "UPDATE Heros SET Hero_Id='".$arr."' WHERE user_id=".$userid.";";
        $conn->exec($sql);
        }
    }
    
}



function getherosfromdatabase($id){
$conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
$sql= "SELECT * FROM Heros WHERE user_id=".$id.";";
$q = $conn->query($sql);
$q->setFetchMode(PDO::FETCH_ASSOC);
$arr=array();
 while ($row = $q->fetch()):
     $arr= htmlspecialchars($row['Hero_Id']);
     
 endwhile;
 return $arr;
}
function retrievesuperhero($id){
    
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://superheroapi.com/api/10221728272130838/'.$id,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);
$httpcode=curl_getinfo($curl,CURLINFO_HTTP_CODE);
curl_close($curl);
$response=json_decode($response);
if ($httpcode!=200){
    die('error please try again later');
}
return $response;

}



function make_blocks($response,$html){
$url= $response->image->url;
$name= $response->name;
$cleanname = str_replace(' ', '', $name);
$powerstats=$response->powerstats;
echo "<div class='alldiv'>";
echo '<img id="'.$cleanname.'" class="thumbnail" src='.$url.'>';
echo '<p>'.$name.'</p>';
echo '</div>';

echo '<div id="'.$cleanname.'1" class="fixedinfo" style="display:none;">
               <div class="titlebar"> <p class="title">'.$name.'</p><button type="button" id="'.$cleanname.'close" class="button" >X</div>
';
echo 'Intelligence<img src="images/brain.jpg" class="brainimg">-'. $powerstats->intelligence;
echo '<br>Strength<img src="images/muscle.png" class="brainimg">-'. $powerstats->strength;
echo '<br>Speed<img src="images/speed.png" class="brainimg">-'. $powerstats->speed;
echo '<br>Durability<img src="images/durability.png" class="brainimg">-'. $powerstats->durability;
echo '<br>Power<img src="images/power.jfif" class="brainimg">-'. $powerstats->power;
echo '<br>Combat<img src="images/combat.png" class="brainimg">-'. $powerstats->combat;
if ($html==1){
    echo "
    <form method='post' action='add.php'>
    <button type='submit' name='add' value='".$response->id."'>Add</button> 
    
    </form>
    ";
}

if ($html==2){
    echo "
    <form method='post' action='browse.php'>
    <button type='submit' name='remove' value='".$response->id."'>Remove</button> 
    
    </form>
    ";
}

if ($html==3){
    echo "
    <form method='post' action='fight.php'>
    <button type='submit' name='fight' value='".$response->id."'>Fight</button> 
    
    </form>
    ";
}

echo '</div>';
return $cleanname;

}

function getusers($username1){

$conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
$sql= "SELECT * FROM users WHERE username='".$username1."' ;";
$q = $conn->query($sql);
$q->setFetchMode(PDO::FETCH_ASSOC);
$arr=array();
$i=0;
 while ($row = $q->fetch()):
     $arr[$i][0]= htmlspecialchars($row['username']);
     $arr[$i][1]= htmlspecialchars($row['password']);
     $arr[$i][2]= htmlspecialchars($row['user_id']);
     $i=$i+1;
 endwhile;
 return $arr;
}

function selectlatestuser(){
    $conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
    $sql= "SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1 ;";
    $q = $conn->query($sql);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    
    $i=0;
    while ($row = $q->fetch()):
        $i= htmlspecialchars($row['user_id']);
    endwhile;
    $i=$i+1;
    return $i;

}

function checkusernamenotexists($username1){
    $conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
    $sql= "SELECT username FROM users;";
    $q = $conn->query($sql);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    
    $arr=array();
    $i=0;
    while ($row = $q->fetch()):
        $arr[$i]= htmlspecialchars($row['username']);
        $i=$i+1;
    endwhile;
    $check;
    if (in_array($username1,$arr)){
        $check=1;
    }else{
        $check-0;
    }
    return $check;
}

function inputnewaccount($username1,$password1){
    $db= new databasecalls();
    $userid=$db->selectlatestuser();
    $check=$db->checkusernamenotexists($username1);
    
    if ($check==0){
        $conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
        $sql= "INSERT INTO users (`user_id`, `username`, `password`) VALUES (".$userid.",'".$username1."','".$password1."')";
        $conn->exec($sql);
        $conn1 = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
        $sql1= "INSERT INTO Heros (`user_id`, `Hero_Id`, `Fav`) VALUES (".$userid.",'30','1')";
        $conn1->exec($sql1);
        return array(true,$userid);
    }else{
        return array(false,$userid);
    }
}

}
?>
