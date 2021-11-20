<?php
/**
 Allows filtering of payments made
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Filter Payments</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<div class="container">
    <h1>View Payments</h1>
    <h3>Use the fields to filter specific results</h3>

    <form method="post" action="">
        <div align="center">
            <table border="0" style="table-layout: fixed">
                <caption>Search Filter</caption>
                <tr>
                    <td><label for="iCode">Invoice Code: </label></td>
                    <td><input type="text" name="iCode" id="iCode"> </td>
                </tr>
                <tr>
                    <td><label for="pCode">Payment Code: </label></td>
                    <td><input type="text" name="pCode" id="pCode"> </td>
                </tr>
                <?php if($_GET['name'] == "")
                { ?>
                <tr>
                    <td><label for="iName">Customer IC: </label></td>
                    <td><input type="number" name="iName" id="iName"> </td>
                </tr>
                <?php }
                if($_GET['id'] == "")
                { ?>
                <tr>
                    <td><label for="sName">Collected By: </label></td>
                    <td><input type="text" name="sName" id="sName"> </td>
                </tr>
                <?php } ?>
                <tr>
                    <td><label for="iType">Payment Type: </label></td>
                    <td><select name="iType" id="iType">
                            <option selected="selected"></option>
                            <option>Cash</option>
                            <option>Credit Card</option>
                            <option>PayPal</option>
                            <option>Bank Transfer</option>
                        </select> </td>
                </tr>
                <tr>
                    <td><label for="fDate">From: </label></td>
                    <td><input type="date" name="fDate" id="fDate" > </td>
                </tr>
                <tr>
                    <td><label for="tDate">To: </label></td>
                    <td><input type="date" name="tDate" id="tDate" > </td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="btnSearch" class="button"> </td>
                </tr>
            </table>
        </div>
    </form>
    <?php
    if($_REQUEST['btnSearch'])
    {

        $SQL = "SELECT tblreceipt.*, tblcustomer.CustName FROM tblreceipt, tblcustomer WHERE tblcustomer.CustIC = tblreceipt.CustIC ";

        if(trim($_POST['iCode'])) $SQL .= "AND invoiceCode LIKE '%".trim($_POST['iCode'])."%' ";
        if(trim($_POST['pCode'])) $SQL .= "AND payCode LIKE '%".trim($_POST['pCode'])."%' ";
        if(trim($_POST['iName'])) $SQL .= "AND tblreceipt.CustIC LIKE '%".trim($_POST['iName'])."%' ";
        if(trim($_POST['sName'])) $SQL .= "AND Collector LIKE '%".trim($_POST['sName'])."%' ";
        if(trim($_POST['iType'])) $SQL .= "AND payType LIKE '%".trim($_POST['iType'])."%' ";
        if(trim($_POST['fDate'])) $SQL .= "AND datePaid >= '".trim($_POST['fDate'])."' ";
        if(trim($_POST['tDate'])) $SQL .= "AND datePaid <= '".trim($_POST['tDate'])."' ";

        if($_GET['id'] != "") $SQL .= "AND Collector = '".strtoupper($_GET['id'])."' ";
        if($_GET['name'] != "") $SQL .= "AND tblreceipt.CustIC = '".$_GET['name']."' ";

        $SQL .= "ORDER BY invoiceCode";
        $SQL = str_replace("WHERE AND", "WHERE", $SQL);
        $SQL = str_replace("WHERE ORDER","ORDER", $SQL);
        $_SESSION['SQL'] = $SQL;
        $id = $_GET['type'];
        echo "<script>location='viewPaymentResult.php?id=".$id."&page=1'</script>";
    }
    ?>
</div>
</body>