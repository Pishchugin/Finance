<?php
include ('./config.php');
date_default_timezone_set('Australia/Hobart');
//$lastYear = strtotime("-1 year", time());

$action_ = new Action();
$categoriesExpenses = $action_->getCategory(2);
$categoriesIncome = $action_->getCategory(1);
$accounts = $action_->getAccounts();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Home Bookkeeping</title>
        <link rel="stylesheet" href="./js/ui/jquery-ui.min.css">
        <link rel=stylesheet type="text/css" href="./css/styles2.css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=1280, minimum-scale=0.25, maximum-scale=2">
        <meta charset="UTF-8">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/highcharts-3d.js"></script>
        <script src="./js/ui/jquery-ui.min.js"></script>
        <script src="./js/tools.js"></script>
        <script src="./js/resources.js"></script>
    </head>
    <body>

        <!--         LOGO 
                <div id="logo">
                    <div id="logoContent">
                        <img id="logoPicture" src="./images/ims_logo.png">
                        <a id="signOut" class="button" href="#" onclick="logOut()">Sign Out</a>
                        <div id="menu">
                            <br>
                            <table>
                                <tr>
                                    <td width="17%"><a href="./home.php"><div style="height: 100%; width: 100%">Home</div></a></td>
                                    <td width="38%"><a href="./moisture_status.php"><div style="height: 100%; width: 100%">Moisture Status Output</div></a></td>
                                    <td width="25%"><a href="./output_weather.php"><div style="height: 100%; width: 100%">Sensor output</div></a></td>
                                    <td width="20%" class="active"><a href="./stations.php"><div style="height: 100%; width: 100%">Settings</div></a></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>-->

        <div id="settingsTab1" onclick="location.replace('./output_weather.php');"></div>
        <div class="grayscale" id="settingsTab2" onclick="location.replace('./output_soil.php');"></div>

        <div id="mainWrapper">
            <div id="subMenu">
                <div class="body"><a href="#"><div style="height: 100%; width: 100%"><span>Soil Type Settings</span></div></a></div>
                <div class="lastPart"></div><div class="triangleLeft"></div><div class="triangleRight"></div>
            </div>

            <div id="pageTitle"> <p>Home Bookkeeping</p> </div>
            <div id="buttonPanel"> 
                <div style="width: 70px;" class="blueButton button" onclick="$('#newExpense').dialog('open');">Add&nbsp;</div>
                <div id="modifyButton" style="width: 70px;" class="blueButton gray-in" onclick="modifyAction($('#expencesTable tr.selected'));">Modify</div>
                <div id="deleteButton" style="width: 70px;" class="redButton gray-in" onclick="deleteAction($('#expencesTable tr.selected'));">Delete</div>
                <div style="width: 90px;" class="blueButton button" onclick="moneyTransfer();">Transfer&nbsp;</div>
            </div>

            <div id="maincontent">
                <div id="expencesWrap">
                    <table>
                        <tr>
                            <td width="115">&nbsp;&nbsp;Select account:</td>
                            <td width="215">
                                <select id='accountBox' class='accountBox'>
                                    <?php
                                    for ($a = 0; $a < sizeof($accounts); $a++) {
                                        echo "<option value='" . $accounts[$a]['Account_ID'] . "'>" . $accounts[$a]['Name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td width="75">Date from: </td>
                            <td width="95"><input id="dateFrom" type='text' value="<?php echo date("01/m/Y"); ?>"></td>
                            <td width="60">Date to: </td>
                            <td width="89"><input id="dateTo" type='text' value="<?php echo date("d/m/Y"); ?>"></td>
                        </tr>
                    </table>
                    <div id="expencesWrapDiv">
                        <table>
                            <tr>
                                <th width="85">Date</th>
                                <th width="345">Category Name</th>
                                <th width="79">Sum</th>
                                <th width="115">Amount left</th>
                            </tr>
                        </table>
                        <div id="expencesDiv">
                            <table id="expencesTable"></table>
                        </div>
                        <table id="summaryTable" hidden>
                            <tr>
                                <th width="83"></th>
                                <th width="338"></th>
                                <th width="194" colspan="2"></th>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="statisticsDiv">
                    <div id="chartContainer"></div> 
                    <table id="statisticsTable" hidden></table>
                </div>
                <br><br>
            </div></div>

        <!-- Modal Window  NEW EXPENSE -->
        <div id="newExpense" title="Add New Action" hidden>
            <div class="modalBackground"></div>
            <div id="modalContent">
                <table id="accountsTable">
                    <tr>
                        <td>Select account: </td>
                        <td>
                            <select class='accountBox'>
                                <?php
                                for ($a = 0; $a < sizeof($accounts); $a++) {
                                    echo "<option value='" . $accounts[$a]['Account_ID'] . "'>" . $accounts[$a]['Name'] . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <table id="operationsTable" class="operationsTable"></table>
            </div>
            <div class="modalFooter">
                <div style="width: 150px;" class="blueButton button" onclick="addExpensesToDB()" autofocus>Add Expenses</div>
            </div>
        </div>


        <!-- Modal Window  MODIFY EXPENSE -->
        <div id="modifyExpense" title="Modify Existing Action" hidden>
            <div class="modalBackground"></div>
            <div id="modalContent">
                <table id="accountsTable">
                    <tr>
                        <td>Select account: </td>
                        <td>
                            <select class='accountBox'>
                                <?php
                                for ($a = 0; $a < sizeof($accounts); $a++) {
                                    echo "<option value='" . $accounts[$a]['Account_ID'] . "'>" . $accounts[$a]['Name'] . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <table id="operationsTable" class="operationsTable">
                    <tr>
                        <td width='89'><input class='dateBox' type='text'></td>
                        <td width='120'><select id='operationBox' class='operationBox'>
                                <option value='1'>Income</option>;
                                <option value='2'>Expense</option>
                            </select>
                        </td>
                        <td width='200'>
                            <select id='categoryBox' class='categoryBox'> </select>
                        </td>
                        <td width='72'><input id='priceBox' class='priceBox' type='text'></td>
                        <td width='18'><div style='width: 28px; height: 25px; margin-top: 3px;' class='redButton flush' onclick="deleteAction($('#expencesTable tr.selected'));">X</div></td>
                    </tr>
                </table>
            </div>
            <div class="modalFooter">
                <div style="width: 100px;" class="blueButton button" onclick="modifyActionInDB()" autofocus>Modify</div>
                <div style="width: 100px;" class="redButton button" onclick="$('.ui-widget-header img').trigger('click');">Cancel</div>
            </div>
        </div>


        <!-- Modal Window  DELETE EXPENSE -->
        <div id="deleteExpense" title="Delete Action" hidden>
            <div class="modalBackground"></div>
            <div id="modalContent">
                <img id="infoImg" src="./images/warnings_yellow_icon.png">
                <p>Are you sure <br>you want to delete this operation?</p>
            </div>
            <div class="modalFooter">
                <div style="width: 100px;" class="blueButton button" onclick="$('.ui-widget-header img').trigger('click');">Cancel</div>
                <div style="width: 100px;" class="redButton button" onclick="deleteActionInDB()" autofocus>Delete</div>
            </div>
        </div>


        <!-- Modal Window  TRANSFER -->
        <div id="transfer" title="Transfer Between Accounts" hidden>
            <div class="modalBackground"></div>
            <div id="modalContent">
                <table id="accountsTable">
                    <tr>
                        <td width="200">Pick up the transfer data: </td>
                        <td width="40"></td>
                        <td width='89'><input class='dateBox' type='text' value="<?php echo date("d/m/Y"); ?>"></td>
                    </tr>
                    <tr></tr><tr></tr>
                    <tr>
                        <td colspan="2" width="240">Select initial account: <br>
                            <select class='transferFrom' id="transferFrom">
                                <?php
                                for ($a = 0; $a < sizeof($accounts); $a++) {
                                    echo "<option value='" . $accounts[$a]['Account_ID'] . "'>" . $accounts[$a]['Name'] . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            Enter amount: <br>
                            <input id="transferFromSum" type="text" class="priceBox" placeholder="$0.00">
                        </td>
                    </tr>
                    <tr></tr><tr></tr>
                    <tr>
                        <td colspan="2">Select target account: <br>
                            <select class='transferTo' id="transferTo">
                                <?php
                                for ($a = 0; $a < sizeof($accounts); $a++) {
                                    echo "<option value='" . $accounts[$a]['Account_ID'] . "'>" . $accounts[$a]['Name'] . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            Enter amount: <br>
                            <input type="text" id="transferToSum" class="priceBox" placeholder="$0.00">
                        </td>
                    </tr>
                </table>

            </div>
            <div class="modalFooter">
                <div style="width: 100px;" class="blueButton button" onclick="changeTransferInDB()" autofocus>Transfer</div>
                <div style="width: 100px;" class="redButton button" onclick="$('.ui-widget-header img').trigger('click');">Cancel</div>
            </div>
        </div>



        <br><br><br>
        <script>
            var categoriesExpenses = <?php echo json_encode($categoriesExpenses); ?>;
            var categoriesIncome = <?php echo json_encode($categoriesIncome); ?>;
            $(".dateBox, #dateFrom, #dateTo").datepicker({dateFormat: 'dd/mm/yy', firstDay: 1});
            $("#accountBox, #dateFrom, #dateTo").change(function () {
                getActions();
            });
            $(document).keypress("m", function (e) {
                if (e.ctrlKey)
                    modifyAction($("#expencesTable tr.selected"));
            });
            $(".priceBox", $("#transfer")).change(function () {
                checkUserInput();
            })
            getActions();


            // GET FULL STATISTICS DATA TO POPULATE THE SUMMARY TABLE 
            function getFullStatistics() {
                var dateFrom = $("#dateFrom").val().split("/");
                var dateTo = $("#dateTo").val().split("/");
                $.post("./engine.php", {
                    action: "getFullStatictics",
                    accountID: $("#accountBox").val(),
                    dateFrom: dateFrom[2] + "-" + dateFrom[1] + "-" + dateFrom[0],
                    dateTo: dateTo[2] + "-" + dateTo[1] + "-" + dateTo[0],
                }, function (ajax) {
                    ajax = eval(ajax);
                    if (!ajax) {
                        alert("Something went wrong!");
                        return;
                    }
                    var limit = 16;
                    if (ajax.length < limit)
                        limit = ajax.length;
                    var html = '<tr><th width="320" class="left">Category</th>';
                    html += '<th width="65">Period</th><th width="65">-1 month</th><th width="65">-1 year</th></tr>';
                    $("#statisticsTable tr").remove().append(html);
                    $("#statisticsTable").append(html);
                    var period = 0, month = 0, year = 0;
                    for (var a = 0; a < limit; a++) {
                        var html = "<tr><td class='left'>" + ajax[a].Category + "</td>";
                        html += "<td>$" + eval(ajax[a].Period).toFixed(2) + "</td>";
                        html += "<td>$" + eval(ajax[a].Month).toFixed(2) + "</td>";
                        html += "<td>$" + eval(ajax[a].Year).toFixed(2) + "</td></tr>";
                        $("#statisticsTable").append(html);
                        period += eval(ajax[a].Period);
                        month += eval(ajax[a].Month);
                        year += eval(ajax[a].Year);
                    }
                    html = "<tr><td class='left'>Total:</td><td>$" + period.toFixed(2) + "</td><td>$" + month.toFixed(2) + "</td><td>$" + year.toFixed(2) + "</td></tr>";
                    $("#statisticsTable").append(html);
                });
            }


            // CREATE A NEW TRANSFER
            function moneyTransfer() {
                $('#transfer').dialog('open');
                $(".transferFrom").val($("#accountBox").val());
                $("#transferFromSum, #transferToSum").val("");
            }


            // SAVE A NEW TRANSFER OR MODIFY THE EXISTING ONE IN THE DB
            function changeTransferInDB() {
                var transferFrom = $("#transferFrom").val();
                var transferTo = $("#transferTo").val();
                var date = $(".dateBox", $("#transfer")).val().split("/");

                if (transferFrom == transferTo) {
                    alert("There is no point to transfer money from/to the same account!");
                    return;
                }
                if (!checkUserInput())
                    return;
                var reference = "";
                $.post("./engine.php", {
                    action: "changeTransfer",
                    transferFrom: transferFrom,
                    transferTo: transferTo,
                    transferFromSum: $("#transferFromSum").val().replace("$", ""),
                    transferToSum: $("#transferToSum").val().replace("$", ""),
                    date: date[2] + "-" + date[1] + "-" + date[0],
                }, function (ajax) {
                    if (!eval(ajax))
                        alert("Something went wrong!")
                    else {
                        getActions();
                        $('.ui-widget-header img').trigger('click');
                    }
                });
            }

            // GET DATA FROM THE DATABASE AND CREATE THE GRAPH
            function createGraph() {
                var dateFrom = $("#dateFrom").val().split("/");
                var dateTo = $("#dateTo").val().split("/");
                $.post("./engine.php", {
                    action: "getGraphData",
                    accountID: $("#accountBox").val(),
                    dateFrom: dateFrom[2] + "-" + dateFrom[1] + "-" + dateFrom[0],
                    dateTo: dateTo[2] + "-" + dateTo[1] + "-" + dateTo[0]
                }, function (ajax) {
                    ajax = eval(ajax);
                    for (var a = 0; a < ajax.length; a++) {
                        ajax[a].y = eval(ajax[a].y);
                    }
                    $('#chartContainer').highcharts().series[0].setData(ajax);
                });
            }


            // DELETE THE EXISTING ACTION
            function deleteAction(row) {
                if (row.length == 0)
                    return;
                $('#deleteExpense').dialog('open');
            }


            // DELETE THE MONEY TRANSFER OPERATION IN THE DB
            function deleteTransfer() {
                var reference = $("td", $("#expencesTable tr.selected")).eq(7).text().split(" ");
                $.post("./engine.php", {
                    action: "deleteTransfer",
                    actionID: $("td", $("#expencesTable tr.selected")).eq(4).text(),
                    referenceID: reference[0]
                }, function (ajax) {
                    getActions();
                    $('.ui-widget-header img').trigger('click');
                });
            }


            // DELETE THE EXISTING ACTION IN DB
            function deleteActionInDB() {
                if ($("td", $("#expencesTable tr.selected")).eq(7).text() != "") {
                    deleteTransfer();
                    return;
                }
                $.post("./engine.php", {
                    action: "deleteAction",
                    actionID: $("td", $("#expencesTable tr.selected")).eq(4).text()
                }, function (ajax) {
                    getActions();
                    $('.ui-widget-header img').trigger('click');
                });
            }


            // MODIFY THE EXISTING ACTION
            function modifyAction(row) {
                if (row.length == 0)
                    return;
                if ($("td", row).eq(7).text() != "") {
                    alert("This operation can be deleted only!");
                    return;
                }
                $('.dateBox', '#modifyExpense').val($("td", row).eq(0).text());
                $("#operationBox", $("#modifyExpense")).val($("td", row).eq(6).text());
                $("#priceBox", $("#modifyExpense")).val($("td", row).eq(2).text()).css("color", "black");
                $(".accountBox", $("#modifyExpense")).val($("#accountBox").val());
                $("#categoryBox", $("#modifyExpense")).empty();
                if ($("td", row).eq(6).text() == "1") {
                    var categories = categoriesIncome;
                } else {
                    var categories = categoriesExpenses;
                }
                for (var i = 0; i < categories.length; i++) {
                    $("#categoryBox", $("#modifyExpense")).append("<option value='" + categories[i][0] + "'>" + categories[i][1] + "</option>");
                }
                $("#categoryBox", $("#modifyExpense")).val($("td", row).eq(5).text());
                parametersInput($("#operationsTable tr:last", $("#modifyExpense")));
                $('#modifyExpense').dialog('open');
            }


            // MODIFY THE EXISTING ACTION IN THE DB
            function modifyActionInDB() {
                if (!checkUserInput())
                    return;
                var operationDate = $('.dateBox', '#modifyExpense').val().split("/");
                $.post("./engine.php", {
                    action: "modifyAction",
                    accountID: $(".accountBox", $("#modifyExpense")).val(),
                    date: operationDate[2] + "-" + operationDate[1] + "-" + operationDate[0],
                    categoryID: $("#categoryBox", $("#modifyExpense")).val(),
                    value: $("#priceBox", $("#modifyExpense")).val().replace("$", ""),
                    actionID: $("td", $("#expencesTable tr.selected")).eq(4).text()
                }, function (ajax) {
                    getActions();
                    $('.ui-widget-header img').trigger('click');
                });
            }


            // RETRIEVE ACTIONS FROM THE DATABASE
            function getActions() {
                var dateFrom = $("#dateFrom").val().split("/");
                var dateTo = $("#dateTo").val().split("/");
                $.post("./engine.php", {
                    action: "getActions",
                    accountID: $("#accountBox").val(),
                    dateFrom: dateFrom[2] + "-" + dateFrom[1] + "-" + dateFrom[0],
                    dateTo: dateTo[2] + "-" + dateTo[1] + "-" + dateTo[0]
                }, function (ajax) {
                    var actions = eval(ajax)[0];
                    var amountLeft = parseFloat(eval(ajax)[1]);
                    $("#expencesTable").empty();
                    var debit = 0, credit = 0;
                    for (var a = 0; a < actions.length; a++) {
                        var html = "<tr class='noselect'>";
                        var date = (actions[a].Date).split("-");
                        html += "<td width='85'>" + date[2] + "/" + date[1] + "/" + date[0] + "</td>";
                        if (actions[a].Category_ID == "16" || actions[a].Category_ID == "17") {
                            var account = (actions[a].Transfer).split(" ");
                            account = $("#accountBox option[value='" + account[1] + "']").text();
                            html += "<td width='344' style='text-align: left;'>" + actions[a].Category_name + account + "</td>";
                        } else
                            html += "<td width='344' style='text-align: left;'>" + actions[a].Category_name + "</td>";
                        html += "<td width='79'>$" + eval(actions[a].Value).toFixed(2) + "</td>";

                        if (actions[a].Operation_ID == "2") {
                            if (actions[a].Category_ID != "16" && actions[a].Category_ID != "17")
                                debit += eval(actions[a].Value)
                            amountLeft -= eval(actions[a].Value);
                        } else {
                            if (actions[a].Category_ID != "16" && actions[a].Category_ID != "17")
                                credit += eval(actions[a].Value);
                            amountLeft += eval(actions[a].Value);
                        }
                        html += "<td width='96'>$" + eval(amountLeft).toFixed(2) + "</td>";
                        html += "<td hidden>" + actions[a].Action_ID + "</td>";
                        html += "<td hidden>" + actions[a].Category_ID + "</td>";
                        html += "<td hidden>" + actions[a].Operation_ID + "</td>";
                        html += "<td hidden>" + actions[a].Transfer + "</td>";
                        html += "<td width='13'style='font-size: 1px;'>99</td>";
                        html += "</tr>";
                        $('#expencesTable').append(html);
                    }
                    dateFrom = new Date(dateFrom[2] + "," + dateFrom[1] + "," + dateFrom[0]);
                    dateTo = new Date(dateTo[2] + "," + dateTo[1] + "," + dateTo[0]);
                    $("#summaryTable th").eq(0).text("Days: " + daysBetween(dateFrom, dateTo));
                    $("#summaryTable th").eq(1).text("Operations over the period: " + $("#expencesTable tr").length);
                    $("#summaryTable th").eq(2).text("Debit: $" + debit.toFixed(0) + "; Credit: $" + credit.toFixed(0));
                    $("#modifyButton, #deleteButton").removeClass("gray-out, button").addClass("gray-in");
                    $("#summaryTable").fadeIn(400);
                    $("#expencesTable tr").dblclick(function () {
                        modifyAction(this);
                    });
                    $("#expencesTable tr").click(function () {
                        $("#expencesTable tr").removeClass("selected");
                        $(this).addClass("selected");
                        $("#modifyButton, #deleteButton").removeClass("gray-in").addClass("gray-out, button");
                    });
                    if (debit > 0) {
                        $('#chartContainer, #statisticsTable').fadeIn(600);
                        createGraph();
                    } else
                        $('#chartContainer, #statisticsTable').fadeOut(600);
                    getFullStatistics();
                });
            }


            // CALCULATE EXPENSES AND FORM ANA ARRAY TO BE ADDED TO THE DB
            function calculateExpenses() {
                var array = [];
                for (var a = 0; a < $("#operationsTable tr", $("#newExpense")).length; a++) {
                    var row = $("#operationsTable tr", $("#newExpense")).eq(a);
                    var date = $(".dateBox", row).val().split("/");
                    date = date[2] + "-" + date[1] + "-" + date[0];
                    var category = eval($("#categoryBox", row).val());
                    var price = eval($("#priceBox", row).val().replace("$", ""));
                    if (category > 0 && price > 0) {
                        var found = false;
                        for (var i = 0; i < array.length; i++) {
                            if (date == array[i].Date && category == array[i].Category) {
                                found = true;
                                array[i].Value += price;
                            }
                        }
                        if (!found)
                            array.push({Date: date, Category: category, Value: price});
                    }
                }
                return array;
            }


            // ADD EXPENSES TO THE DATABASE
            function addExpensesToDB() {
                if (!checkUserInput())
                    return;
                $.post("./engine.php", {
                    action: "saveActions",
                    accountID: $(".accountBox", $("#newExpense")).val(),
                    array: calculateExpenses()
                }, function (ajax) {
                    if (!eval(ajax))
                        alert("Something went wrong!")
                    else {
                        getActions();
                        $('.ui-widget-header img').trigger('click');
                    }
                });
            }


            // HANDLES USER'S PARAMETERS INPUT
            function parametersInput(row) {
                $(".dateBox", row).datepicker({dateFormat: 'dd/mm/yy'});
                $(".categoryBox, .priceBox, .operationBox", row).unbind().change(function () {
                    var row = $(this).closest("tr");
                    if (!checkUserInput())
                        return;
                    if ($(this).hasClass("operationBox")) {
                        $("#categoryBox", row).empty();
                        if ($(this).val() == "1")
                            var categories = categoriesIncome;
                        else
                            var categories = categoriesExpenses;
                        for (var i = 0; i < categories.length; i++) {
                            $("#categoryBox", row).append("<option value='" + categories[i][0] + "'>" + categories[i][1] + "</option>");
                        }
                        $("#categoryBox", row).val("0");
                    }

                    if (eval($("#priceBox", row).val().replace("$", "")) > 0 && eval($("#categoryBox", row).val()) > 0)
                        if ($('tr:last', $("#operationsTable")).index() == row.index())
                            addExpensesTableRow(true);
                });
            }


            // CHECK IF THE USER'S INPUT IS CORRECT
            function checkUserInput() {
                var correct = true;
                $(".priceBox").each(function () {
                    $(this).val($(this).val().replace("$", ""));
                    if (isNaN($(this).val())) {
                        runEffect($(this), "shake", "hide");
                        correct = false;
                        $(this).css("color", "red");
                    } else {
                        $(this).css("color", "black");
                        if ($(this).val() != "")
                            $(this).val("$" + eval($(this).val()).toFixed(2));
                    }
                });
                return correct;
            }


            // ADD A NEW ROW TO THE EXPENSES TABLE
            function addExpensesTableRow(animate) {
                var date = "<?php echo date("d/m/Y"); ?>";
                var html = "<tr><td width='89'><input class='dateBox' type='text' value='" + date + "'></td>";
                html += "<td width='120'><select id='operationBox' class='operationBox'>";
                html += "<option value='1'>Income</option>";
                html += "<option value='2' selected>Expense</option>";
                html += "</select></td>";
                html += "<td width='200'><select id='categoryBox' class='categoryBox'>";
                for (var i = 0; i < categoriesExpenses.length; i++) {
                    html += "<option value='" + categoriesExpenses[i][0] + "'>" + categoriesExpenses[i][1] + "</option>";
                }
                html += "</select></td>";
                html += "<td width='72'><input id='priceBox' class='priceBox' type='text' placeholder='$0.00'></td>";
                html += "<td width='18'><div style='width: 28px; height: 25px; margin-top: 3px;' class='redButton flush' onclick='removeModalRow($(this));'>X</div></td>";
                html += "</tr>";
                if ($('tr:last', $("#operationsTable")).index() < 0)
                    $("#operationsTable").html("<tbody>" + html + "</tbody>");
                else {
                    var prevData = $('tr:last', $("#operationsTable")).find(".dateBox").val();
                    $('tr:last', $("#operationsTable")).after(html);
                    $('tr:last', $("#operationsTable")).find(".dateBox").val(prevData);
                }
                $('tr:last', $("#operationsTable")).find("#categoryBox").val("0");
                if (animate) {
                    $("#newExpense").dialog("widget").animate({top: "-=15px"}, 600);
                    $('#newExpense').animate({height: $('#newExpense').height() + 30});
                }

                parametersInput($('tr:last', $("#operationsTable")));
            }


            // REMOVE A SELECTED ROW FROM THE NEW EXPENSES TABLE
            function removeModalRow(button) {
                var row = button.closest("tr");
                var last = false;
                if ($('tr:last', $("#operationsTable")).index() == row.index())
                    last = true;
                row.remove();
                if (!last) {
                    $("#newExpense").dialog("widget").animate({top: "+=15px"}, 600);
                    $('#newExpense').animate({height: $('#newExpense').height() - 30});
                } else
                    addExpensesTableRow(false);
            }




        </script>
    </body>
</html>
