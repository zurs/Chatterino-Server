<?php

function createUser($dbh, $nickname, $password){
    $sql = "INSERT INTO users (nickname, password) VALUES ('" + $nickname + "', '" + $password + "');";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    echo("true");
}

function authorize($dbh, $nickname, $password){
    $sql = "SELECT password FROM users WHERE username='" + $username + "';";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    if($password == $stmt->fetch["password"]){
        return true;
    }
    else{
        return false;
    }
}

function checkForNewMessages($dbh, $lastMessage){
    $sql = "SELECT messageID FROM chat ORDER BY datetime LIMIT 1;";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    if($lastMessage == $stmt->fetch["messageID"]){
        echo("false");
    }
    else{
        returnLastMessages($dbh, $lastMessage);
    }
}

function returnLastMessages($dbh, $lastMessage){
    $sql = "SELECT * FROM chat ORDER BY datetime LIMIT 20;";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    
    $iterations = 0;
    while($message = $stmt->fetch()){
        if($message["messageID"] == $lastMessage){
            returnAmountMessages($dbh, $iterations);
        }
        $iterations = $iterations + 1;
    }
}

function returnAmountMessages($dbh, $amount){
    $sql = "SELECT * FROM chat ORDER BY datetime LIMIT " + $amount;
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    echo(json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)));
}

function newConnection($dbh, $username){
    $sql = "INSERT INTO chat (senderNick, messageID, messageText, datetime) VALUES ('Server', NULL, '" + $username + " just connected', NOW());";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    echo("true");
}

function newMessage($dbh, $senderNick, $messageText){
    $sql = "INSERT INTO chat (senderNick, messageID, messageText, datetime) VALUES ('" + $senderNick + "', NULL, '" + $messageText + "', NOW());";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
}

?>