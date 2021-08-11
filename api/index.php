<?php

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Origin');

header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Acces-Control-Request-Method");

header("Access-Control-Allow-Methods: GET");

header("Allow: GET");

include('conexion.php');


$data = new conexion();
$data->connect();
$info = $data->show();
$infosensor = $data->showsensor();
$infolight = $data->showlight();
$infosaver = $data->showsaver();
$json =json_decode($info, True);
$jsonsensor =json_decode($infosensor, True);
$jsonlight =json_decode($infolight, True);
$jsonsaver =json_decode($infosaver, True);
$i=0;
if($_GET['data']=='lightsaver'){
echo '{"lightsaver":[';
foreach($json as $show){
	if($i<3){ $coma=","; $i = $i+1;}else{$coma = "";}
	echo '{"id":'.$show["id"].
	',"place":"'.$show["place"].'"'.
	',"lastUpdate":"'.$show["lastUpdate"].'"}'.$coma;
}
echo ']}';
}
else if($_GET['data']=='sensor'){
echo '{"sensors":[';
foreach($jsonsensor as $show){
	if($i<3){ $coma=","; $i = $i+1;}else{$coma = "";}
	echo '{"n":'.$show["n"].
	',"name":"'.$show["name"].'"'.
	',"status":"'.$show["status"].'"}'.$coma;
}
echo ']}';
}
else if($_GET['data']=='light'){
echo '{"lights":[';
foreach($jsonlight as $show){
	if($i<3){ $coma=","; $i = $i+1;}else{$coma = "";}
	echo '{"id":'.$show["id"].
	',"name":"'.$show["name"].'"'.
	',"status":"'.$show["status"].'"'.
	',"value":"'.$show["value"].'"'.
	',"temperature":"'.$show["temperature"].'"'.
	',"valueTemp":"'.$show["valueTemp"].'"}'.$coma;
}
echo ']}';
}
else if($_GET['data']=='saver'){
echo '{"saver":[';
foreach($jsonsaver as $show){
	if($i<3){ $coma=","; $i = $i+1;}else{$coma = "";}
	echo '{"n":'.$show["n"].
	',"t":"'.$show["t"].'"'.
	',"motion":"'.$show["motion"].'"'.
	',"illumination":"'.$show["illumination"].'"}'.$coma;
}
echo ']}';
}
?>