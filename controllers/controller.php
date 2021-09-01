<?php
declare(strict_types=1);

require_once('../models/db.php');

$DB = new DB();
// echo ("db works!");
header('Access-Control-Allow-Origin: *');

function validate(string $pwd){
    $uppercase = preg_match('@[A-Z]@', $pwd);
    $lowercase = preg_match('@[a-z]@', $pwd);
    $number    = preg_match('@[0-9]@', $pwd);
    $specialChars = preg_match('@[^\w]@', $pwd);

    if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($pwd) < 8) {
        header('Content-Type: application/json');
        echo json_encode("error");
        return false;
    }else{
        return true;
    }
}


if(isset($_POST["search"])){
    $results = $DB->select("SELECT * FROM `users` WHERE `Name` LIKE ?",["%{$_POST['search']}%"]);
    header('Content-Type: application/json');
    echo json_encode($results == false ? null : $results);
}

if(isset($_POST["register"])){
    if(isset($_POST["name"]) && isset($_POST["uname"]) && isset($_POST["password"])){
        if(validate($_POST["password"])){
            $sql = $DB->execute('INSERT INTO `users` (`Name`, `Username`, `Password`, `Timestamp`) VALUES (:name, :uname, :password, CURRENT_TIMESTAMP)',array(':name'=>$_POST["name"],':uname'=>$_POST["uname"],':password'=>password_hash($_POST["password"],PASSWORD_BCRYPT)));
        }
    }else{
        die();
    }
}

// $results = $DB->select("SELECT * FROM `users` WHERE `Name` LIKE 'samanthaB' ");
// print_r($results);

?>