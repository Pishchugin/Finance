<?php

include ('./config.php');
$action = $_POST['action'];
$result = "";

switch ($action) {
    // OPERATIONS MODULE
    case "saveActions":
        $result = (new Action())->saveActions($_POST['accountID'], $_POST['array']);
        break;

    case "getActions":
        $result = (new Action())->getActions($_POST['dateFrom'], $_POST['dateTo'], $_POST['accountID']);
        break;

    case "modifyAction":
        $result = (new Action())->modifyAction($_POST['accountID'], $_POST['date'], $_POST['categoryID'], $_POST['value'], $_POST['actionID']);
        break;

    case "deleteAction":
        $result = (new Action())->deleteAction($_POST['actionID']);
        break;

    case "changeTransfer":
        $result = (new Action())->changeTransfer($_POST['transferFrom'], $_POST['transferTo'], $_POST['transferFromSum'], $_POST['transferToSum'], $_POST['date']);
        break;

    case "deleteTransfer":
        $result = (new Action())->deleteTransfer($_POST['actionID'], $_POST['referenceID']);
        break;

    // STATISTICS MODULE
    case "getGraphData":
        $result = (new Statistics())->getGraphData($_POST['dateFrom'], $_POST['dateTo'], $_POST['accountID']);
        break;
    
     case "getFullStatictics":
        $result = (new Statistics())->getFullStatictics($_POST['dateFrom'], $_POST['dateTo'], $_POST['accountID']);
        break;
}

if (gettype($result) == "array")
    echo "(" . json_encode($result) . ")";
else
    echo json_encode($result);
?>
