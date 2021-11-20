<?php
/**
 Filters items that were sold
 */
error_reporting(E_COMPILE_ERROR);
session_start();
include("database.php");
include("Menu.php");
?>
<title>Filter Items Sold</title>
<link rel="stylesheet" type = "text/css" href="Default%20Theme.css" />

<body>
<div class="container">
    <h1>View Items Sold</h1>
    <h3>Use the fields to filter specific results</h3>

    <form method="post" action="">
        <div align="center">
            <table border="0" style="table-layout: fixed">
                <caption>Search Filter</caption>
                <tr>
                    <td><label for="iID">Item ID: </label></td>
                    <td><input type="text" name="iID" id="iID"> </td>
                </tr>
                <tr>
                    <td><label for="iCode">Item Code: </label></td>
                    <td><input type="text" name="iCode" id="iCode"> </td>
                </tr>
                <tr>
                    <td><label for="iName">Item Name: </label></td>
                    <td><input type="text" name="iName" id="iName"> </td>
                </tr>
                <tr>
                    <td><label for="iDesc">Item Description: </label></td>
                    <td><textarea name="iDesc" id="iDesc" cols="35" rows="5"></textarea> </td>
                </tr>
                <tr>
                    <td><label for="iType">Item Type: </label></td>
                    <td><select name="iType" id="iType">
                            <option selected="selected"></option>
                            <option value="Lens">Lens</option>
                            <option value="Frame">Frame</option>
                            <option value="Contact">Contacts</option>
                            <option value="Solution">Contact Fluid</option>
                        </select> </td>
                </tr>
                <tr>
                    <td><label for="iPrice">Price(RM): </label></td>
                    <td><input type="number" step="0.01" min="0" name="iPrice" id="iPrice"> </td>
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

        $SQL = "SELECT *, SUM(Quantity) AS Quan FROM tblinventory, tblinvoice, tblitemsold
        WHERE tblinvoice.invoiceCode = tblitemsold.invoiceCode AND tblitemsold.itemId = tblinventory.ItemId ";

        if(trim($_POST['iID'])) $SQL .= "AND tblitemsold.itemId LIKE '%".trim($_POST['iID'])."%' ";
        if(trim($_POST['iCode'])) $SQL .= "AND Code LIKE '%".trim($_POST['iCode'])."%' ";
        if(trim($_POST['iName'])) $SQL .= "AND ItemName LIKE '%".trim($_POST['iName'])."%' ";
        if(trim($_POST['iDesc'])) $SQL .= "AND ItemDesc LIKE '%".trim($_POST['iDesc'])."%' ";
        if(trim($_POST['iType'])) $SQL .= "AND ItemType LIKE '%".trim($_POST['iType'])."%' ";
        if(trim($_POST['iPrice'])) $SQL .= "AND tblitemsold.Price = ".trim($_POST['iPrice'])." ";
        if(trim($_POST['fDate'])) $SQL .= "AND tblinvoice.DateSold >= '".trim($_POST['fDate'])."' ";
        if(trim($_POST['tDate'])) $SQL .= "AND tblinvoice.DateSold <= '".trim($_POST['tDate'])."' ";

        $SQL .= "GROUP By tblitemsold.itemId";
        $SQL = str_replace("WHERE AND", "WHERE", $SQL);
        $SQL = str_replace("WHERE ORDER","ORDER", $SQL);
        $_SESSION['SQL'] = $SQL;
        $id = $_GET['type'];
        echo "<script>location='viewSoldResult.php?id=".$id."&page=1'</script>";
    }
    ?>
</div>
</body>