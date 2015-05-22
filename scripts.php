<?php

function createUser($dbh, $nickname, $password){
    $sql = "INSERT INTO users (nickname, password) VALUES ('{$nickname}', '{$password}');";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    echo("true");
}

function authorize($dbh, $nickname, $password){
    $sql = "SELECT password FROM users WHERE nickname='{$nickname}';";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $dbpass = $stmt->fetch()["password"];
    if($password == $dbpass){
        return true;
    }
    else{
        return false;
    }
}

function returnAmountMessages($dbh, $amount){
    $sql = "SELECT * FROM chat ORDER BY messageID DESC LIMIT {$amount}";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    echo(json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)));
}

function newConnection($dbh, $nickname){
    $message = "{$nickname} just connected";
    newMessage($dbh, "Server", $message);
    echo("true");
}

function newMessage($dbh, $senderNick, $messageText){
    $sql = "INSERT INTO chat (senderNick, messageID, messageText, datetime) VALUES ('{$senderNick}', NULL, '{$messageText }', NOW());";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
}

?>
