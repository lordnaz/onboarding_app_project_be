<?php
    require_once("Model/_process.php");

    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
    }

    $status = $process->checkUserRecord($email);

    if($status!=0){
        $user = array();
        $user = $process->getUserRecord($email,$password);

        if($user!=0){
            $_SESSION["loginID"]    = $user["login_id"];
            $_SESSION["userTypeID"] = $user["user_type_id"];
            $_SESSION["status"]     = $user["status"];
            $_SESSION["login"]      = 1;
            echo 1;
        }else{
            echo 2;
        }
    }else{
        echo $status;
    }
?> 