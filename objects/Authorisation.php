<?php

/**
 * Description of Authorisation
 * @author Alexander Pishchugin
 */
class Authorisation {

    // User Log Out
    public function logOut() {
        if (session_id()) {
            $auth = new Authorisation();
            $credentials = $auth->getCredentials();
            session_destroy();
            return $credentials;
        } else {
            return false;
        }
    }

    // checks if the credentials typed by the user are correct
    public function checkCredentials($user, $password) {
        $mysqli = DBconnection::Connect();

        $query = "SELECT * FROM `user` WHERE `password`='$password' AND `username`='$user'";
        $result = $mysqli->query($query);

        if ($result->num_rows == 1) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $_SESSION['userID'] = $row['user_ID'];
            $_SESSION['user'] = $user;
            $_SESSION['password'] = $password;
            $_SESSION['firstname'] = $row['first_name'];
            $_SESSION['lastname'] = $row['last_name'];
            $_SESSION['email'] = $row['email'];
            return true;
        } else {
            return false;
        }
    }

    // checks if the user is logged in to the system or not
    public function checkAccess() {
        $access = false;
        if (isset($_SESSION['userID'])) {
            if ($_SESSION['userID'] > 0)
                $access = true;
        }
        if (!$access) {
            echo "<script>alert('You need to login first to access this page!');";
            echo "location.replace('./index.php');</script>";
            return false;
        } else
            return true;
    }

    // returns the ID of the user currently logged in
    public function getUserID() {
        return $_SESSION['userID'];
    }

    // returns the username of the user currently logged in
    public function getUsername() {
        return $_SESSION['user'];
    }

    // returns all the information related to the user currently logged in
    public function getCredentials() {
        return array("username" => $_SESSION['user'], "userID" => $_SESSION['userID'], "password" => $_SESSION['password'],
            "firstname" => $_SESSION['firstname'], "lastname" => $_SESSION['lastname'], "email" => $_SESSION['email']);
    }

    // sends a security code to the email address and saves it in the DB
    public function sendCode($user, $link) {
        $mysqli = DBconnection::Connect();
        $auth = new Authorisation();
        $query = "SELECT * FROM `user` WHERE `username`='$user'";
        $result = $mysqli->query($query);
        $result_cnt = $result->num_rows;

        if ($result_cnt == 1) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $userID = $row['user_ID'];
            $firstname = $row['first_name'];
            $email = $row['email'];
            if ($email == "")
                return array("", "no_email");
            else {
                $code = rand(100000, 999999);
                $query = "INSERT INTO `secure_code`(`code`, `User_user_ID`) VALUES ('$code', '$userID')";
                $result = $mysqli->query($query);

                $auth->sendEmail($firstname, $user, $email, $code, $link);
                return array($firstname, "success");
            }
        } else
            return array($user, "not_registered");
    }

    // changes user's password in the database
    public function changePassword($userID, $passw, $email) {
        $mysqli = DBconnection::Connect();

        $query = "UPDATE `user` SET `password`='$passw', `email`='$email' WHERE `user_ID`='$userID'";
        $result = $mysqli->query($query);

        if (!$result)
            return false;
        else {
            $_SESSION['password'] = $passw;
        return true;}
    }

    // checks if the security code a new password/confirmation been typed are correct
    public function recoveryPassword($user, $newPassw, $secCode) {
        $mysqli = DBconnection::Connect();
        $query = "SELECT * FROM `user`, `secure_code` WHERE `user`.`username`='$user' AND `user`.`user_ID`=`secure_code`.`User_user_ID` AND `secure_code`.`code`='$secCode'";
        $result = $mysqli->query($query);

        if ($result->num_rows == 1) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $userID = $row['user_ID'];
            $firstname = $row['first_name'];

            date_default_timezone_set("Australia/Tasmania");
            $expired = date('Y-m-d H:i:s', strtotime('+1 hour', strtotime($row['created'])));
            $curDate = date('Y-m-d H:i:s');
            if ($curDate < $expired) {
                $query = "UPDATE `user` SET `password`='$newPassw' WHERE `user_ID`='$userID'";
                $result = $mysqli->query($query);
                return array($firstname, "success");
            } else
                return array($user, "expired");
        } else {
            return array($user, "no_code");
        }
    }

// sends an email message to the given email address
    private function sendEmail($firstname, $user, $to, $code, $link) {
        require_once('./plugins/phpmailer/Mail.php');
        $from = "Smart Service Systems <noreply.sss.utas@gmail.com>";
        $subject = "Password recovery code";
        $body = "Dear " . $firstname . "!<br><br>";
        $body.= "Your password recovery code is <b>" . $code . ".</b> Please enter this code on the webpage using your browser or follow ";
        $body.="<a href='" . $link . "?user=" . $user . "&code=" . $code . "'>the link</a>.";
        $body.="<br><br><i>Kind regards, <br>Smart Service Systems Team</i>";

        $headers = array(
            'From' => $from,
            'To' => $to,
            'Content-Type' => 'text/html; charset=ISO-8859-1',
            'Subject' => $subject
        );

        $smtp = Mail::factory('smtp', array(
                    'host' => 'ssl://smtp.gmail.com',
                    'port' => '465',
                    'auth' => true,
                    'username' => 'noreply.sss.utas@gmail.com',
                    'password' => 'DavCarAl2016'
        ));

        $mail = $smtp->send($to, $headers, $body);
        if (PEAR::isError($mail)) {
            echo("<p>" . $mail->getMessage() . "</p>");
        }
    }

}
