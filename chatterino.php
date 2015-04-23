<?php
require("scripts.php");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");

$config = simplexml_load_file("config.xml");
$dbhost = $config->dbhost;
$dbname = $config->dbname;
$dbusername = $config->dbusername;
$dbpasswd = $config->dbpasswd;

$dbh = new PDO("mysql:host={$dbhost}; charset=utf8; dbname={$dbname};", $dbusername, $dbpasswd);

$post_data = @file_get_contents("php://input");
$data = json_decode($post_data);
$nickname = $data->nickname;
$password = $data->password;
$function = $data->function;


if($function == "createUser"){ // Om en ny användare skulle vilja skapas
    createUser($dbh, $nickname, $password);
}
else{
    if(authorize($dbh, $nickname, $password)){ // Autentierar användaren och tillåter ytterliga funktioner
        
        switch($function){
            case "connection":
                newConnection($dbh, $nickname);
                break;
            case "checkForNewMessages":
                $lastMessage = $data->lastMessage;
                checkForNewMessages($dbh, $lastMessage);
                break;
            case "newMessage":
                $messageText = $data->messageText;
                newMessage($dbh, $nickname, $messageText);
                break;
        }
        
    }
}


?>