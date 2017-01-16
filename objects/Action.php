<?php

/**
 * Description of Action
 * @author Alexander Pishchugin
 */
class Action {

    // GET THE LIST OF ACCOUNTS
    public function getAccounts() {
        $query = "SELECT * FROM `accounts`";
        $result = DBconnection::Connect()->query($query);
        $array = array();
        while ($row = $result->fetch_assoc()) {
            $array[] = $row;
        }
        return $array;
    }

    // GET CATEGORIES BY THE OPERATION TYPE (INCOME/EXPENSE)
    public function getCategory($type) {
        $query = "SELECT * FROM `categories` WHERE `Operation_ID`='$type' AND `Enabled`='1' ORDER BY `Category_name` ASC";
        $result = DBconnection::Connect()->query($query);
        $array[] = array("0", "Please select a category");

        while ($row = $result->fetch_assoc()) {
            $array[] = array($row['Category_ID'], $row['Category_name']);
        }
        return $array;
    }

    // SAVE OPERATIONS IN THE DATABASE
    public function saveActions($accountID, $array) {
        $mysqli = DBconnection::Connect();
        $result = true;
        for ($a = 0; $a < sizeof($array); $a++) {
            $date = $array[$a]['Date'];
            $categoryID = $array[$a]['Category'];
            $value = $array[$a]['Value'];

            $query = "SELECT * FROM `actions` WHERE `Date`='$date' AND `Accounts_ID`='$accountID' AND `Categories_ID`='$categoryID'";
            $result = $mysqli->query($query);

            if ($result->num_rows > 0) {
                $result = $result->fetch_assoc();
                $value += $result['Value'];
                $actionID = $result['Action_ID'];
                $query = "UPDATE `actions` SET `Value`='$value' WHERE `Action_ID`='$actionID'";
            } else
                $query = "INSERT INTO `actions`(`Date`, `Accounts_ID`, `Categories_ID`, `Value`) "
                        . "VALUES ('$date', '$accountID', '$categoryID', '$value')";
            $result = $mysqli->query($query);
        }
        if (!$result)
            return false;
        else
            return true;
    }

    // GET OPERATIONS FROM THE DATABASE
    public function getActions($dateFrom, $dateTo, $accountID) {
        $mysqli = DBconnection::Connect();

        $query = "SELECT * FROM `accounts` WHERE `Account_ID`='$accountID'";
        $result = $mysqli->query($query)->fetch_assoc();
        $amountLeft = $result['Value'];

        $query = "SELECT * FROM `actions`, `categories` WHERE `actions`.`Accounts_ID`='$accountID' AND "
                . "`actions`.`Categories_ID`=`categories`.`Category_ID` ORDER BY `actions`.`Date` ASC";
        $result = $mysqli->query($query);
        $array = array();
        while ($row = $result->fetch_assoc()) {
            if (date_create($row['Date']) >= date_create($dateFrom) && date_create($row['Date']) <= date_create($dateTo)) {
                $array[] = array("Action_ID" => $row['Action_ID'], "Date" => $row['Date'], "Account_ID" => $row['Accounts_ID'],
                    "Category_ID" => $row['Category_ID'], "Category_name" => $row['Category_name'],
                    "Value" => $row['Value'], "Operation_ID" => $row['Operation_ID'], "Transfer" => $row['Transfer']);
            } else {
                if ($row['Operation_ID'] == "1") {
                    $amountLeft += $row['Value'];
                } else {
                    $amountLeft -= $row['Value'];
                }
            }
        }
        return array($array, $amountLeft);
    }

    // MODIFY THE ACTION IN THE DATABASE
    public function modifyAction($accountID, $date, $categoryID, $value, $actionID) {
        $query = "UPDATE `actions` SET `Date`='$date',`Accounts_ID`='$accountID',`Categories_ID`='$categoryID',"
                . "`Value`='$value' WHERE `Action_ID`='$actionID'";
        return DBconnection::Connect()->query($query);
    }

    // DELETE THE ACTION IN THE DATABASE
    public function deleteAction($actionID) {
        $query = "DELETE FROM `actions` WHERE `Action_ID`='$actionID'";
        return DBconnection::Connect()->query($query);
    }

    // CREATE A NEW TRANSFER OR MODIFY THE EXISTING ONE IN THE DATABASE
    public function changeTransfer($transferFrom, $transferTo, $transferFromSum, $transferToSum, $date) {
        $mysqli = DBconnection::Connect();
        // $query = "SELECT MAX(`action_ID`) AS max FROM `actions`";
        $query = "SHOW TABLE STATUS FROM `finance` WHERE `name` LIKE 'actions'";
        $ID = $mysqli->query($query)->fetch_assoc();

        $newID = $ID['Auto_increment'] + 1;
        $reference = "$newID $transferTo $transferToSum";

        $query = "INSERT INTO `actions`(`Date`, `Accounts_ID`, `Categories_ID`, `Value`, `Transfer`) "
                . "VALUES ('$date', '$transferFrom', '17', '$transferFromSum', '$reference')";
        if (!$result = $mysqli->query($query))
            return false;

        $newID = $ID['Auto_increment'];
        $reference = "$newID $transferFrom $transferFromSum";
        $query = "INSERT INTO `actions`(`Date`, `Accounts_ID`, `Categories_ID`, `Value`, `Transfer`) "
                . "VALUES ('$date', '$transferTo', '16', '$transferToSum', '$reference')";
        if (!$result = $mysqli->query($query))
            return false;
        return true;
    }

    // DELETE THE MONEY TRANSFER OPERATION IN THE DB
    public function deleteTransfer($actionID, $referenceID) {
        if ($result = $this->deleteAction($actionID))
            $result = $this->deleteAction($referenceID);
        return $result;
    }

}
