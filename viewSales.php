<?php
/**
 Allows the user to filter for invoices
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Filter Invoices</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<div class="container">
    <h1>View Invoices</h1>
    <h3>Use the fields to filter specific results</h3>

    <form method="post" action="">
        <div align="center">
            <table border="0" style="table-layout: fixed">
                <caption>Search Filter</caption>
                <tr>
                    <td><label for="iCode">Invoice Code: </label></td>
                    <td><input type="text" name="iCode" id="iCode"> </td>
                </tr>
                <?php if($_GET['name'] == "")
                { ?>
                <tr>
                    <td><label for="iName">Customer IC: </label></td>
                    <td><input type="number" name="iName" id="iName"> </td>
                </tr>
                <?php }
                if($_GET['id'] == "")
                {?>
                <tr>
                    <td><label for="sName">Sold By: </label></td>
                    <td><input type="text" name="sName" id="sName"> </td>
                </tr>
                <?php } ?>
                <tr>
                    <td><label for="iType">Invoice Status: </label></td>
                    <td><select name="iType" id="iType">
                            <option selected="selected"></option>
                            <option value="P">Paid</option>
                            <option value="U">Unpaid</option>
                        </select> </td>
                </tr>
                <tr>
                    <td><label for="fDate">From: </label></td>
                    <td><input type="date" name="fDate" id="fDate" > </td>
                </tr>
                <tr>
                    <td><label for="tDate">To: </label></td>
                    <td><input type="date" name="tDate" id="tDate"> </td>
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

        $SQL = "SELECT tblinvoice.*,tblcustomer.CustName FROM tblinvoice, tblcustomer WHERE tblcustomer.CustIC = tblinvoice.CustIc ";

        if(trim($_POST['iCode'])) $SQL .= "AND invoiceCode LIKE '%".trim($_POST['iCode'])."%' ";
        if(trim($_POST['iName'])) $SQL .= "AND tblinvoice.CustIc LIKE '%".trim($_POST['iName'])."%' ";
        if(trim($_POST['sName'])) $SQL .= "AND Username LIKE '%".trim($_POST['sName'])."%' ";
        if(trim($_POST['iType'])) $SQL .= "AND tblinvoice.Status LIKE '%".trim($_POST['iType'])."%' ";
        if(trim($_POST['fDate'])) $SQL .= "AND DateSold >= '".trim($_POST['fDate'])."' ";
        if(trim($_POST['tDate'])) $SQL .= "AND DateSold <= '".trim($_POST['tDate'])."' ";

        if($_GET['id'] != "") $SQL .= "AND Username = '".strtoupper($_GET['id'])."' ";
        if($_GET['name'] != "") $SQL .= "AND tblinvoice.CustIc = '".$_GET['name']."' ";

        $SQL .= "ORDER BY invoiceCode ASC ";
        $SQL = str_replace("WHERE AND", "WHERE", $SQL);
        $SQL = str_replace("WHERE ORDER","ORDER", $SQL);
        $_SESSION['SQL'] = $SQL;
        $id = $_GET['type'];
        echo "<script>location='viewSalesResult.php?id=".$id."&page=1'</script>";
    }
    ?>
</div>
</body>