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
    $func = $data->func;


    if($func == "createUser"){ // Om en ny användare skulle vilja skapas
        createUser($dbh, $nickname, $password);
    }
    else{
        if(authorize($dbh, $nickname, $password)){ // Autentierar användaren och tillåter ytterliga funktioner
            switch($func){
                case "connection":
                    newConnection($dbh, $nickname);
                    break;
                case "checkForNewMessages":
                    returnAmountMessages($dbh, 10);
                    break;
                case "newMessage":
                    $messageText = $data->messageText;
                    newMessage($dbh, $nickname, $messageText);
                    break;
            }
        }
        else{
            echo("Not legit");
        }
    }
?>
