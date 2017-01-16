<?php

/**
 * Description of Statistics
 * @author Alexander Pishchugin
 */
class Statistics {

    // GET DATA FROM THE DATABASE AND CREATE THE GRAPH
    public function getGraphData($dateFrom, $dateTo, $accountID) {
        $result = $this->getWholeData($dateFrom, $dateTo, $accountID);
        $data = array();
        while ($row = $result->fetch_assoc()) {
            if ($row['Category_ID'] != 16 && $row['Category_ID'] != 17)
                $data[] = array("name" => $row['Category_name'], "y" => $row['sum']);
        }
        return $data;
    }

    // GET FULL STATISTICS DATA TO POPULATE THE SUMMARY TABLE 
    public function getFullStatictics($dateFrom, $dateTo, $accountID) {
        $result = $this->getWholeData($dateFrom, $dateTo, $accountID);
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $lastMonthFrom = date("Y/m/d", strtotime("-1 month", strtotime($dateFrom)));
            $lastMonthTo = date("Y/m/d", strtotime("-1 month", strtotime($dateTo)));
            $lastMonthAmount = 0;
            $lastMonthData = $this->getWholeData($lastMonthFrom, $lastMonthTo, $accountID);
            while ($line = $lastMonthData->fetch_assoc()) {
                if ($line['Category_ID'] == $row['Category_ID'])
                    $lastMonthAmount = $line['sum'];
            }

            $lastYearFrom = date("Y/m/d", strtotime("-1 year", strtotime($dateFrom)));
            $lastYearTo = date("Y/m/d", strtotime("-1 year", strtotime($dateTo)));
            $lastYearAmount = 0;
            $lastYearData = $this->getWholeData($lastYearFrom, $lastYearTo, $accountID);
            while ($line = $lastYearData->fetch_assoc()) {
                if ($line['Category_ID'] == $row['Category_ID'])
                    $lastYearAmount = $line['sum'];
            }

            if ($row['Category_ID'] != 16 && $row['Category_ID'] != 17)
                $data[] = array("Category" => $row['Category_name'], "Period" => $row['sum'], "Month" => $lastMonthAmount, "Year" => $lastYearAmount);
        }
        return $data;
    }

    // INTERNAL FUNCTION TO GET DATA FOR STATISTICS
    private function getWholeData($dateFrom, $dateTo, $accountID) {
        $query = "SELECT `categories`.`Category_name`, `categories`.`Category_ID`, SUM(`actions`.`Value`) AS sum FROM `actions`, `categories` "
                . "WHERE `actions`.`Categories_ID`=`categories`.`Category_ID` AND `categories`.`Operation_ID`='2' "
                . "AND `actions`.`Accounts_ID`='$accountID' AND `actions`.`Date`>='$dateFrom' AND "
                . "`actions`.`Date`<='$dateTo' GROUP BY `categories`.`Category_name` ORDER BY sum DESC";
        return DBconnection::Connect()->query($query);
    }

}
