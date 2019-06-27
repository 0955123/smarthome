<?php 

require("phpMQTT.php");
require("database.php");

$database = new Database();
$topics = array();

$topics['classrooms/5.4/temperature'] = array("qos" => 0, "function" => "procmsg");
$topics['classrooms/5.4/humidity'] = array("qos" => 0, "function" => "procmsg");
$topics['classrooms/5.4/carbondioxide'] = array("qos" => 0, "function" => "procmsg");
$topics['classrooms/5.4/status'] = array("qos" => 0, "function" => "procmsg");

$server = "test.mosquitto.org:1883";   // change if necessary
$port = 1883;                     	// change if necessary
$username = "";                   	// set your username
$password = "";                   	// set your password
$client_id = uniqid(); 				// make sure this is unique for connecting to sever - you could use uniqid()
$mqtt = new bluerhinos\phpMQTT($server, $port, $client_id);

if(!$mqtt->connect(true, NULL, $username, $password)) {
	exit(1);
}

$mqtt->subscribe($topics, 0);
while($mqtt->proc()){
		
}
$mqtt->close();

function procmsg($topic, $msg){
	echo "Msg Recieved: " . date("r") . "\n";
	echo "Topic: {$topic}\n\n";
	echo "\t$msg\n\n";

	$database = new Database();
	$database->updateMqttRow($topic, $msg);
}