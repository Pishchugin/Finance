<?php

/**
 * Description of DBconnection
 * @author Alexander Pishchugin
 */
abstract class DBconnection {

    // perform connection to the DB
    static public function Connect($server = null, $user = null, $password = null, $db = null) {
        if ($server == null)
            $server = "pishchugin.ddns.net";
        if ($user == null)
            $user = "ap18";
        if ($password == null)
            $password = "194932";
        if ($db == null)
            $db = "finance";

        $mysqli = new mysqli(gethostbyname($server), $user, $password, $db);
        if (mysqli_connect_errno()) {
            echo "Connect failed: " . mysqli_connect_error() . "<br>";
            return false;
        } else
            return $mysqli;
    }

}
